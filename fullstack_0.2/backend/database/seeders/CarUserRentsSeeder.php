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
        
        ### 3 - Előrendelésre lefoglalva -> update lesz!
        $folyamatban = Rentinprocess::factory(50)->make()->toArray();
        DB::table('car_user_rents')->insert($folyamatban);

        $lezart = Renthistory::factory(1000)->make()->toArray();
        DB::table('car_user_rents')->insert($lezart);
    }
}
