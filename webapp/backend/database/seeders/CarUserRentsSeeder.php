<?php

namespace Database\Seeders;

use App\Models\CarUserRent;
use App\Models\Renthistory;
use App\Models\Rentinprocess;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarUserRentsSeeder extends Seeder
{
    public function run(): void
    {
        ### rentstatus -ok ###

       
        ### 2 - lezárt
        for ($i = 0; $i <= 1000; $i++) {
            $oneRent = Renthistory::factory()->make()->toArray();

            $parkolasok = $oneRent['parkolasok'] ?? [];
            unset($oneRent['parkolasok']);

            DB::table('car_user_rents')->insert($oneRent);
            $rentId = DB::getPdo()->lastInsertId();

            ## ParkolasSeeder a CarUserRentParkings táblába
            (new CarUserRentParkingSeeder)->carUserRent_ParkolasokSeeder($rentId, $parkolasok);
        }
         ### 1 - folyamatban
         $folyamatban = Rentinprocess::factory(50)->make()->toArray();
         DB::table('car_user_rents')->insert($folyamatban);
    }
}
class CarUserRentParkingSeeder extends Seeder
{
    public function run() {}

    public function carUserRent_ParkolasokSeeder($rentId, $parkolasok)
    {
        if (!empty($parkolasok)) {
            foreach ($parkolasok as $parkolas) {
                DB::table('car_user_rent_parkings')->insert([
                    'rent_id' => $rentId,
                    'parking_start' => $parkolas['kezd'],
                    'parking_end'   => $parkolas['veg'],
                    'parking_minutes' => round($parkolas['hossza_perc'], 0),
                    'parking_cost' => intval($parkolas['total_cost']),
                ]);
            }
        }
    }
}
