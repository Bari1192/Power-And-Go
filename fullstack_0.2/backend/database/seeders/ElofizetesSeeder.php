<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ElofizetesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('elofizetesek')->insert([

            [
                'elofiz_nev' => 'Power',
                'havi_dij' => null,
                'eves_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elofiz_nev' => 'Power-Plus',
                'havi_dij' => 490,
                'eves_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elofiz_nev' => 'Power-Premium',
                'havi_dij' => 1690,
                'eves_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elofiz_nev' => 'Power-VIP',
                'havi_dij' => 5990,
                'eves_dij' => 59900,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
