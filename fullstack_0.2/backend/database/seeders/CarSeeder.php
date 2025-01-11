<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;
use Illuminate\Support\Facades\DB;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $cars = Car::factory(500)->make()->toArray();
        DB::table('cars')->insert($cars);
    }
}
