<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FlottaTipusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('flotta_tipusok')->insert(
            [
                ['gyarto' => 'VW', 'tipus' => 'e-up!', 'teljesitmeny' => 18, 'vegsebesseg' => 130, 'gumimeret' => '165|65-R15', 'hatotav' => 135, 'created_at' => now(), 'updated_at' => now()],
                ['gyarto' => 'VW', 'tipus' => 'e-up!', 'teljesitmeny' => 36, 'vegsebesseg' => 130, 'gumimeret' => '165|65-R15', 'hatotav' => 265, 'created_at' => now(), 'updated_at' => now()],
                ['gyarto' => 'Skoda', 'tipus' => 'Citigo-e-iV', 'teljesitmeny' => 36, 'vegsebesseg' => 130, 'gumimeret' => '165|65-R16', 'hatotav' => 265, 'created_at' => now(), 'updated_at' => now()],
                ['gyarto' => 'Renault', 'tipus' => 'Kangoo-Z.E.', 'teljesitmeny' => 33, 'vegsebesseg' => 130, 'gumimeret' => '165|65-R15', 'hatotav' => 245, 'created_at' => now(), 'updated_at' => now()],
                ['gyarto' => 'Opel', 'tipus' => 'Vivaro-e', 'teljesitmeny' => 75, 'vegsebesseg' => 192, 'gumimeret' => '165|65-R16', 'hatotav' => 340, 'created_at' => now(), 'updated_at' => now()],
                ['gyarto' => 'KIA', 'tipus' => 'Niro-EV', 'teljesitmeny' => 65, 'vegsebesseg' => 167, 'gumimeret' => '165|65-R17', 'hatotav' => 460, 'created_at' => now(), 'updated_at' => now()],
            ]
        );
    }
}
