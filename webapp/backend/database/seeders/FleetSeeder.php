<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FleetSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('fleets')->insert(
            [
                ['manufacturer' => 'VW', 'carmodel' => 'e-up!', 'motor_power' => 18, 'top_speed' => 130, 'tire_size' => '165|65-R15', 'driving_range' => 135],
                ['manufacturer' => 'Renault', 'carmodel' => 'Kangoo-Z.E.', 'motor_power' => 33, 'top_speed' => 130, 'tire_size' => '165|65-R15', 'driving_range' => 245],
                ['manufacturer' => 'VW', 'carmodel' => 'e-up!', 'motor_power' => 36, 'top_speed' => 130, 'tire_size' => '165|65-R15', 'driving_range' => 265],
                ['manufacturer' => 'Skoda', 'carmodel' => 'Citigo-e-iV', 'motor_power' => 36, 'top_speed' => 130, 'tire_size' => '165|65-R16', 'driving_range' => 265],
                ['manufacturer' => 'Opel', 'carmodel' => 'Vivaro-e', 'motor_power' => 75, 'top_speed' => 192, 'tire_size' => '165|65-R16', 'driving_range' => 340],
                ['manufacturer' => 'KIA', 'carmodel' => 'Niro-EV', 'motor_power' => 65, 'top_speed' => 167, 'tire_size' => '165|65-R17', 'driving_range' => 460],
            ]
        );
    }
}
