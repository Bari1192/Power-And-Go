<?php

namespace App\Policies;

use App\Models\Bill;
use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BillService
{
    public static array $chargingFines = [
        1 => ['buntetes' => 30_000],
        2 => ['buntetes' => 50_000],
        3 => ['buntetes' => 30_000],
        4 => ['buntetes' => 50_000],
        5 => ['buntetes' => 50_000],
    ];
    public function createRentBill(Car $car, User $user, $rental): void
    {
        DB::transaction(function () use ($car, $user, $rental) {
            $charges = DB::table('car_user_rent_charges')
                ->where('rent_id', $rental->id)
                ->selectRaw('SUM(credits) as credits, SUM(charged_kw) as charged_kw')
                ->first();

            Bill::create([
                'rent_id' => $rental->id,
                'bill_type' => 'rental',
                'user_id' => $user->id,
                'person_id' => $user->person_id,
                'car_id' => $car->id,
                'rent_start' => Carbon::parse($rental->rent_start)->format('Y-m-d H:i:s'),
                'rent_close' => Carbon::parse($rental->rent_close)->format('Y-m-d H:i:s'),
                'distance' => $rental->distance ?? 0,
                'parking_minutes' => $rental->parking_minutes ?? 0,
                'driving_minutes' => $rental->driving_minutes ?? 0,
                'total_cost' => $rental->rental_cost ?? 0,
                'credits' => $charges->credits ?? 0,
                'charged_kw' => $charges->charged_kw ?? 0,
                'invoice_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'invoice_status' => 'pending'
            ]);
        });
    }

    public static function createChargingFine(Car $car, User $user, $rental): void
    {
        DB::transaction(function () use ($car, $user, $rental) {
            Bill::create([
                'bill_type' => 'charging_penalty',
                'user_id' => $user->id,
                'person_id' => $user->person_id,
                'car_id' => $car->id,
                'rent_id' => $rental->id,
                'rent_start' => Carbon::parse($rental->rent_start)->format('Y-m-d H:i:s'),
                'rent_close' => Carbon::parse($rental->rent_close)->format('Y-m-d H:i:s'),
                'total_cost' => self::$chargingFines[$car->category_id]['buntetes'],
                'invoice_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'invoice_status' => 'pending'
            ]);
        });
    }
}