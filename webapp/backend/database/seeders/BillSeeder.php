<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Car;
use App\Models\Renthistory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ## [ SZABÁLYZAT ] ##
        $kategoriak = [
            1 => ['min_toltes' => 9.0, 'buntetes' => 30000],
            2 => ['min_toltes' => 6.0, 'buntetes' => 50000],
            3 => ['min_toltes' => 4.5, 'buntetes' => 30000],
            4 => ['min_toltes' => 4.0, 'buntetes' => 50000],
            5 => ['min_toltes' => 4.0, 'buntetes' => 50000],
        ];
        # 1-es autó besorolas [E-up - 18kw] típus
        # 2-es besorolas [Renault Kangoo - 33 kW] típus
        # 3-as besorolas [Citigo & E-up - 36 kW] típus
        # 4-es besorolas [Kia Niro - 65 kW] típus
        # 5-ös besorolas [ Opel Vivaro - 75 kW] típus

        $lezartBerlesek = DB::table('car_user_rents')
            ->where('rentstatus', 2) // Csak a lezárt bérlések
            ->get();

        $szamlaAdatok = [];
        $buntetesAdatok = [];

        foreach ($lezartBerlesek as $rental) {
            // Lekérjük a user és car adatokat a kapcsolatokból
            $felhasznalo = User::find($rental->user_id);
            $auto = Car::find($rental->car_id);

            // Számla adatok generálása
            $szamlaAdatok[] = [
                'bill_type' => 'rental',
                'user_id' => $felhasznalo->id,
                'person_id' => $felhasznalo->person_id,
                'car_id' => $auto->id,
                'rent_start_date' => $rental->rent_start_date,
                'rent_start_time' => $rental->rent_start_time,
                'rent_end_date' => $rental->rent_end_date,
                'rent_end_time' => $rental->rent_end_time,
                'driving_distance' => $rental->driving_distance,
                'parking_minutes' => $rental->parking_minutes,
                'driving_minutes' => $rental->driving_minutes,
                'total_cost' => $rental->rental_cost,
                'invoice_date' => now(),
                'invoice_status' => 'pending',
            ];

            // Büntetés generálása a töltöttségi szint alapján
            $kategoriak = [
                1 => ['min_toltes' => 9.0, 'buntetes' => 30000],
                2 => ['min_toltes' => 6.0, 'buntetes' => 50000],
                3 => ['min_toltes' => 4.5, 'buntetes' => 30000],
                4 => ['min_toltes' => 4.0, 'buntetes' => 50000],
                5 => ['min_toltes' => 4.0, 'buntetes' => 50000],
            ];

            $autoKategoria = $rental->category_id;
            $zarasToltesSzazalek = $rental->end_percent;

            if (isset($kategoriak[$autoKategoria]) && $zarasToltesSzazalek < $kategoriak[$autoKategoria]['min_toltes']) {
                $buntetesAdatok[] = [
                    'bill_type' => 'charging_penalty',
                    'user_id' => $felhasznalo->id,
                    'person_id' => $felhasznalo->person_id,
                    'car_id' => $auto->id,
                    'rent_start_date' => $rental->rent_start_date,
                    'rent_start_time' => $rental->rent_start_time,
                    'rent_end_date' => $rental->rent_end_date,
                    'rent_end_time' => $rental->rent_end_time,
                    'driving_distance' => $rental->driving_distance,
                    'parking_minutes' => $rental->parking_minutes,
                    'driving_minutes' => $rental->driving_minutes,
                    'total_cost' => $kategoriak[$autoKategoria]['buntetes'],
                    'invoice_date' => now(),
                    'invoice_status' => 'pending',
                ];
            }
        }

        // Adatok mentése az adatbázisba
        if (!empty($szamlaAdatok)) {
            DB::table('bills')->insert($szamlaAdatok);
        }

        if (!empty($buntetesAdatok)) {
            DB::table('bills')->insert($buntetesAdatok);
        }
    }
}
