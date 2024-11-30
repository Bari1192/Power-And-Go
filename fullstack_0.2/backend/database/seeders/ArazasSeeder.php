<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArazasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('arazasok')->insert([
            # E-up              18 kW       1-es besorolas
            # Renault Kangoo    33 kW       2-es besorolas
            # E-up & Citigo     36 kW       3-as besorolas
            # Kia Niro          65 kW       4-es besorolas
            # Opel Vivaro       75 kW       5-ös besorolas
            
            ## [Power] árak * [5 kategoria]
            [
                'elofiz_id' => 1,
                'auto_besorolas' => 1,
                'berles_ind' => 380,
                'vez_perc' => 105,
                'kedv_vez' => null,             # null -> nem elérhető
                'parkolas_perc' => 85,
                'foglalasi_perc' => 85,
                'kedv_parkolas_perc' => 55,
                'napidij' => 17680,
                'napi_km_limit' => 100,
                'km_dij' => 48,
                'repter_ki_felar' => 2490,
                'repter_be_felar' => 990,
                'repter_ki_terminal' => 3190,
                'repter_be_terminal' => 1680,
                'zona_nyit_felar' => 0,
                'zona_zar_felar' => 390,
            ],
            # E-up 36kW && Citigo 36kW
            [
                'elofiz_id' => 1,
                'auto_besorolas' => 3,
                'berles_ind' => 380,
                'vez_perc' => 105,
                'kedv_vez' => null,             # null -> nem elérhető
                'parkolas_perc' => 85,
                'foglalasi_perc' => 85,
                'kedv_parkolas_perc' => 55,
                'napidij' => 17680,
                'napi_km_limit' => 100,
                'km_dij' => 48,
                'repter_ki_felar' => 2490,
                'repter_be_felar' => 990,
                'repter_ki_terminal' => 3190,
                'repter_be_terminal' => 1680,
                'zona_nyit_felar' => 0,
                'zona_zar_felar' => 390,
            ],
            ## [Power-Plus] árak * [5 kategoria]
            [],
            ## [Power-Prémium] árak * [5 kategoria]
            [],
            ## [Power-VIP] árak * [5 kategoria]
            [],

        ]);
    }
}
