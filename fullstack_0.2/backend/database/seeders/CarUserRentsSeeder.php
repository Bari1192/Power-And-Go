<?php

namespace Database\Seeders;

use App\Models\Renthistory;
use App\Models\Rentinprocess;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarUserRentsSeeder extends Seeder
{
    public function run(): void
    {
        ### rentstatus -ok ###
        ### 1 - folyamatban
        ### 2 - lezárt

        $folyamatban = Rentinprocess::factory(100)
            ->make()
            ->map(function ($rent) {
                $rent['rentstatus'] = 1; // Státusz: folyamatban
                return $rent->toArray(); // Átalakítás tömbbé
            })
            ->toArray();
        DB::table('car_user_rents')->insert($folyamatban);

        // Lezárt bérlések generálása
        $lezart = Renthistory::factory(1000)
            ->make()
            ->map(function ($rent) {
                $rent['rentstatus'] = 2; // Státusz: lezárt
                return $rent->toArray(); // Átalakítás tömbbé
            })
            ->toArray();
        DB::table('car_user_rents')->insert($lezart);
    }
}
