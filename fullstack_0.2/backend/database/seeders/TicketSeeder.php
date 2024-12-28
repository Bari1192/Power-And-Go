<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 100; $i++) {
            DB::table('tickets')->insert([
                'car_id' => fake()->numberBetween(1,50), ## első 50 kocsira, nem akarok keresgélni.
                'status_id' => fake()->numberBetween(1, 6),
                'description' => fake()->text(80),
                'bejelentve' => now(),
            ]);
        }
    }
}
