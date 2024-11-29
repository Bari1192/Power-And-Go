<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FlottaTipusokSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('flotta_tipusok')->insert(
            [
                ['flotta_id' => 1, 'gyarto' => 'VW', 'tipus' => 'e-up!', 'teljesitmeny' => 18, 'vegsebesseg' => 130, 'gumimeret' => '165|65-R15', 'hatotav' => 130],
                ['flotta_id' => 2, 'gyarto' => 'VW', 'tipus' => 'e-up!', 'teljesitmeny' => 36, 'vegsebesseg' => 130, 'gumimeret' => '165|65-R15', 'hatotav' => 300],
                ['flotta_id' => 3, 'gyarto' => 'Skoda', 'tipus' => 'Citigo-e-iV', 'teljesitmeny' => 36, 'vegsebesseg' => 130, 'gumimeret' => '165|65-R16', 'hatotav' => 300],
                ['flotta_id' => 4, 'gyarto' => 'Renault', 'tipus' => 'Kangoo-Z.E.', 'teljesitmeny' => 45, 'vegsebesseg' => 130, 'gumimeret' => '165|65-R15', 'hatotav' => 285],
                ['flotta_id' => 5, 'gyarto' => 'Opel', 'tipus' => 'Vivaro-e', 'teljesitmeny' => 50, 'vegsebesseg' => 192, 'gumimeret' => '165|65-R16', 'hatotav' => 320],
                ['flotta_id' => 6, 'gyarto' => 'KIA', 'tipus' => 'Niro-EV', 'teljesitmeny' => 65, 'vegsebesseg' => 167, 'gumimeret' => '165|65-R17', 'hatotav' => 350],
            ]
        );
    }
}
