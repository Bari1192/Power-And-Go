<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BillSeeder extends Seeder
{
    private array $category = [
        1 => ['min_toltes' => 9.0, 'buntetes' => 30000], // E-up - 18kw
        2 => ['min_toltes' => 6.0, 'buntetes' => 50000], // Renault Kangoo - 33 kW
        3 => ['min_toltes' => 4.5, 'buntetes' => 30000], // Citigo & E-up - 36 kW
        4 => ['min_toltes' => 4.0, 'buntetes' => 50000], // Kia Niro - 65 kW
        5 => ['min_toltes' => 4.0, 'buntetes' => 50000], // Opel Vivaro - 75 kW
    ];

    public function run(): void
    {
        // Lezárt bérlések lekérése
        $lezartBerlesek = DB::table('car_user_rents')
            ->where('rentstatus', 2)
            ->get();

        $szamlaAdatok = [];
        $buntetesAdatok = [];

        foreach ($lezartBerlesek as $rental) {
            $user = User::find($rental->user_id);
            $car = Car::find($rental->car_id);
            $charges = DB::table('car_user_rent_charges')
                ->where('rent_id', $rental->id)
                ->select(
                    DB::raw('SUM(credits) as credits'),
                    DB::raw('SUM(charged_kw) as charged_kw')
                )
                ->first();

            // Alap számla
            $szamlaAdatok[] = [
                'bill_type' => 'rental',
                'user_id' => $user->id,
                'person_id' => $user->person_id,
                'car_id' => $car->id,
                'rent_start' => is_string($rental->rent_start) ? $rental->rent_start : $rental->rent_start->format('Y-m-d H:i:s'),
                'rent_close' => is_string($rental->rent_close) ? $rental->rent_close : $rental->rent_close->format('Y-m-d H:i:s'),
                'distance' => $rental->distance ?? 0,  // NULL védelem
                'parking_minutes' => $rental->parking_minutes ?? 0,
                'driving_minutes' => $rental->driving_minutes ?? 0,
                'total_cost' => $rental->rental_cost ?? 0,
                'credits' => $charges->credits ?? 0,
                'charged_kw' => $charges->charged_kw ?? 0,
                'invoice_date' => now()->format('Y-m-d H:i:s'),
                'invoice_status' => 'pending'
            ];

            // Büntetés ellenőrzése
            $carKategoria = $rental->category_id;
            $zarasToltesSzazalek = $rental->end_percent;

            if (
                isset($this->category[$carKategoria]) &&
                $zarasToltesSzazalek < $this->category[$carKategoria]['min_toltes'] &&
                $zarasToltesSzazalek !== null
            ) {

                $buntetesAdatok[] = [
                    'bill_type' => 'charging_penalty',
                    'user_id' => $user->id,
                    'person_id' => $user->person_id,
                    'car_id' => $car->id,
                    'rent_start' => is_string($rental->rent_start) ? $rental->rent_start : $rental->rent_start->format('Y-m-d H:i:s'),
                    'rent_close' => is_string($rental->rent_close) ? $rental->rent_close : $rental->rent_close->format('Y-m-d H:i:s'),
                    'distance' => $rental->distance ?? 0,
                    'parking_minutes' => $rental->parking_minutes ?? 0,
                    'driving_minutes' => $rental->driving_minutes ?? 0,
                    'total_cost' => $this->category[$carKategoria]['buntetes'],
                    'invoice_date' => now()->format('Y-m-d H:i:s'),
                    'invoice_status' => 'pending'
                ];

                // Autó státuszának frissítése
                DB::table('cars')
                    ->where('id', $car->id)
                    ->update(['status' => 7]);
            }
        }

        // Adatok mentése
        if (!empty($szamlaAdatok)) {
            DB::table('bills')->insert($szamlaAdatok);

        }

        if (!empty($buntetesAdatok)) {
            DB::table('bills')->insert($buntetesAdatok);
        }
    }
}
