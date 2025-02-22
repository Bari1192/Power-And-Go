<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\User;
use App\Policies\BillService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BillSeeder extends Seeder
{
    public function run(): void
    {
        // Lezárt bérlések lekérése
        $lezartBerlesek = DB::table('car_user_rents')
            ->where('rentstatus', 2)
            ->get();

        $billService = new BillService();

        $carIds = $lezartBerlesek->pluck('car_id')->unique();
        $userIds = $lezartBerlesek->pluck('user_id')->unique();
        $cars = Car::whereIn('id', $carIds)->get()->keyBy('id');
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        foreach ($lezartBerlesek as $rental) {
            $user = $users[$rental->user_id];
            $car = $cars[$rental->car_id];

            $billService->createRentBill($car, $user, $rental);

            if ($car->status === 7) {
                $billService->createChargingFine($car, $user, $rental);
                
            }
        }
    }
}
