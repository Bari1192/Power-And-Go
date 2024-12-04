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

            # null -> nem elérhető ≠ 0!

            ## [Power] árak * [5 kategoria]
            # E-up | 18 kW | 1-es besorolas |
            [
                'elofiz_azon' => 1,
                'auto_besorolas' => 1,
                'berles_ind' => 380,
                'vez_perc' => 105,
                'kedv_vez' => null,
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
                'harom_ora_dij' => null,
                'hat_ora_dij' => null,
                'tizenketto_ora_dij' => null,
                'hetvegi_napi_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            # Renault Kangoo | 33 kW | 2-es besorolas |
            [
                'elofiz_azon' => 1,
                'auto_besorolas' => 2,
                'berles_ind' => 380,
                'vez_perc' => 105,
                'kedv_vez' => null,
                'parkolas_perc' => 85,
                'foglalasi_perc' => 85,
                'kedv_parkolas_perc' => 55,
                'napidij' => 23680,
                'napi_km_limit' => 100,
                'km_dij' => 48,
                'repter_ki_felar' => null,
                'repter_be_felar' => null,
                'repter_ki_terminal' => null,
                'repter_be_terminal' => null,
                'zona_nyit_felar' => null,
                'zona_zar_felar' => null,
                'harom_ora_dij' => null,
                'hat_ora_dij' => null,
                'tizenketto_ora_dij' => null,
                'hetvegi_napi_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            # E-up, Citigo | 36kW | 3-as besorolas |
            [
                'elofiz_azon' => 1,
                'auto_besorolas' => 3,
                'berles_ind' => 380,
                'vez_perc' => 105,
                'kedv_vez' => null,
                'parkolas_perc' => 85,
                'foglalasi_perc' => 85,
                'kedv_parkolas_perc' => 55,
                'napidij' => 18680,
                'napi_km_limit' => 100,
                'km_dij' => 48,
                'repter_ki_felar' => 2490,
                'repter_be_felar' => 990,
                'repter_ki_terminal' => 3190,
                'repter_be_terminal' => 1680,
                'zona_nyit_felar' => 0,
                'zona_zar_felar' => 390,
                'harom_ora_dij' => null,
                'hat_ora_dij' => null,
                'tizenketto_ora_dij' => null,
                'hetvegi_napi_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            # Kia Niro | 65 kW | 4-es besorolas |
            [
                'elofiz_azon' => 1,
                'auto_besorolas' => 4,
                'berles_ind' => 1990,
                'vez_perc' => 78,           # Csak ha a 24 órán túl (napi bérlés minimum! túlmegy akkor pluszban.)
                'kedv_vez' => null,
                'parkolas_perc' => 78,
                'foglalasi_perc' => null,
                'kedv_parkolas_perc' => null,
                'napidij' => 25680,
                'napi_km_limit' => 100,
                'km_dij' => 48,
                'repter_ki_felar' => null,
                'repter_be_felar' => null,
                'repter_ki_terminal' => null,
                'repter_be_terminal' => null,
                'zona_nyit_felar' => null,
                'zona_zar_felar' => null,
                'harom_ora_dij' => null,
                'hat_ora_dij' => null,
                'tizenketto_ora_dij' => null,
                'hetvegi_napi_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            # Opel Vivaro | 75 kW | 5-ös besorolas |
            [
                'elofiz_azon' => 1,
                'auto_besorolas' => 5,
                'berles_ind' => 1990,
                'vez_perc' => 69,           # Csak ha a 24 órán túl (napi bérlés minimum! túlmegy akkor pluszban.)
                'kedv_vez' => null,
                'parkolas_perc' => 69,
                'foglalasi_perc' => null,
                'kedv_parkolas_perc' => null,
                'napidij' => 22678,
                'napi_km_limit' => 15,
                'km_dij' => 48,
                'repter_ki_felar' => null,
                'repter_be_felar' => null,
                'repter_ki_terminal' => null,
                'repter_be_terminal' => null,
                'zona_nyit_felar' => null,
                'zona_zar_felar' => null,
                'harom_ora_dij' => 12473,
                'hat_ora_dij' => 15875,
                'tizenketto_ora_dij' => 19276,
                'hetvegi_napi_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            ####################################
            ## [Power-Plus] árak * [5 kategoria]
            # E-up | 18 kW | 1-es besorolas |
            [
                'elofiz_azon' => 2,
                'auto_besorolas' => 1,
                'berles_ind' => 290,
                'vez_perc' => 83,
                'kedv_vez' => null,
                'parkolas_perc' => 59,
                'foglalasi_perc' => 59,
                'kedv_parkolas_perc' => 35,
                'napidij' => 14680,
                'hetvegi_napi_dij' => 10680,
                'napi_km_limit' => 100,
                'km_dij' => 48,
                'repter_ki_felar' => 2490,
                'repter_be_felar' => 990,
                'repter_ki_terminal' => 3190,
                'repter_be_terminal' => 1680,
                'zona_nyit_felar' => 0,
                'zona_zar_felar' => 390,
                'harom_ora_dij' => null,
                'hat_ora_dij' => null,
                'tizenketto_ora_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            # Renault Kangoo - 33 kW
            [
                'elofiz_azon' => 2,
                'auto_besorolas' => 2,
                'berles_ind' => 290,
                'vez_perc' => 83,
                'kedv_vez' => null,
                'parkolas_perc' => 59,
                'foglalasi_perc' => 59,
                'kedv_parkolas_perc' => 35,
                'napidij' => 20680,
                'hetvegi_napi_dij' => 20680,
                'napi_km_limit' => 100,
                'km_dij' => 48,
                'repter_ki_felar' => null,
                'repter_be_felar' => null,
                'repter_ki_terminal' => null,
                'repter_be_terminal' => null,
                'zona_nyit_felar' => null,
                'zona_zar_felar' => null,
                'harom_ora_dij' => null,
                'hat_ora_dij' => null,
                'tizenketto_ora_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            # E-up, Citigo | 36kW | 3-as besorolas |
            [
                'elofiz_azon' => 2,
                'auto_besorolas' => 3,
                'berles_ind' => 290,
                'vez_perc' => 83,
                'kedv_vez' => null,
                'parkolas_perc' => 59,
                'foglalasi_perc' => 59,
                'kedv_parkolas_perc' => 35,
                'napidij' => 15680,
                'hetvegi_napi_dij' => 11680,
                'napi_km_limit' => 100,
                'km_dij' => 48,
                'repter_ki_felar' => 2490,
                'repter_be_felar' => 990,
                'repter_ki_terminal' => 3190,
                'repter_be_terminal' => 1680,
                'zona_nyit_felar' => 0,
                'zona_zar_felar' => 390,
                'harom_ora_dij' => null,
                'hat_ora_dij' => null,
                'tizenketto_ora_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            # Kia Niro | 65 kW | 4-es besorolas |
            [
                'elofiz_azon' => 2,
                'auto_besorolas' => 4,
                'berles_ind' => 1990,
                'vez_perc' => 78,           # Csak ha a 24 órán túl (napi bérlés minimum! túlmegy akkor pluszban.)
                'kedv_vez' => null,
                'parkolas_perc' => 78,
                'foglalasi_perc' => null,
                'kedv_parkolas_perc' => null,
                'napidij' => 22680,
                'hetvegi_napi_dij' => null,
                'napi_km_limit' => 100,
                'km_dij' => 48,
                'repter_ki_felar' => null,
                'repter_be_felar' => null,
                'repter_ki_terminal' => null,
                'repter_be_terminal' => null,
                'zona_nyit_felar' => null,
                'zona_zar_felar' => null,
                'harom_ora_dij' => null,
                'hat_ora_dij' => null,
                'tizenketto_ora_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            # Opel Vivaro | 75 kW | 5-ös besorolas |
            [
                'elofiz_azon' => 2,
                'auto_besorolas' => 5,
                'berles_ind' => 4500,
                'vez_perc' => 69,           # Csak ha a 24 órán túl (napi bérlés minimum! túlmegy akkor pluszban.)
                'kedv_vez' => null,
                'parkolas_perc' => 69,
                'foglalasi_perc' => null,
                'kedv_parkolas_perc' => null,
                'napidij' => 20128,
                'hetvegi_napi_dij' => null,
                'napi_km_limit' => 15,
                'km_dij' => 48,
                'repter_ki_felar' => null,
                'repter_be_felar' => null,
                'repter_ki_terminal' => null,
                'repter_be_terminal' => null,
                'zona_nyit_felar' => null,
                'zona_zar_felar' => null,
                'harom_ora_dij' => 11070,
                'hat_ora_dij' => 14090,
                'tizenketto_ora_dij' => 17109,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            #######################################
            ## [Power-Prémium] árak * [5 kategoria]
            # E-up | 18 kW | 1-es besorolas |
            [
                'elofiz_azon' => 3,
                'auto_besorolas' => 1,
                'berles_ind' => 290,
                'vez_perc' => 83,
                'kedv_vez' => null,
                'parkolas_perc' => 59,
                'foglalasi_perc' => 59,
                'kedv_parkolas_perc' => 35,
                'napidij' => 14680,
                'hetvegi_napi_dij' => null,
                'napi_km_limit' => 100,
                'km_dij' => 48,
                'repter_ki_felar' => 2490,
                'repter_be_felar' => 990,
                'repter_ki_terminal' => 3190,
                'repter_be_terminal' => 1680,
                'zona_nyit_felar' => 0,
                'zona_zar_felar' => 390,
                'harom_ora_dij' => null,
                'hat_ora_dij' => null,
                'tizenketto_ora_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            # Renault Kangoo - 33 kW
            [
                'elofiz_azon' => 3,
                'auto_besorolas' => 2,
                'berles_ind' => 290,
                'vez_perc' => 78,
                'kedv_vez' => null,
                'parkolas_perc' => 59,
                'foglalasi_perc' => 59,
                'kedv_parkolas_perc' => 35,
                'napidij' => 14680,
                'hetvegi_napi_dij' => null,
                'napi_km_limit' => 100,
                'km_dij' => 48,
                'repter_ki_felar' => null,
                'repter_be_felar' => null,
                'repter_ki_terminal' => null,
                'repter_be_terminal' => null,
                'zona_nyit_felar' => null,
                'zona_zar_felar' => null,
                'harom_ora_dij' => null,
                'hat_ora_dij' => null,
                'tizenketto_ora_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            # E-up, Citigo | 36kW | 3-as besorolas |
            [
                'elofiz_azon' => 3,
                'auto_besorolas' => 3,
                'berles_ind' => 1990,
                'vez_perc' => 59,
                'kedv_vez' => null,
                'parkolas_perc' => 59,
                'foglalasi_perc' => 59,
                'kedv_parkolas_perc' => 35,
                'napidij' => 15680,
                'hetvegi_napi_dij' => null,
                'napi_km_limit' => 100,
                'km_dij' => 48,
                'repter_ki_felar' => null,
                'repter_be_felar' => null,
                'repter_ki_terminal' => null,
                'repter_be_terminal' => null,
                'zona_nyit_felar' => null,
                'zona_zar_felar' => null,
                'harom_ora_dij' => null,
                'hat_ora_dij' => null,
                'tizenketto_ora_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            # Kia Niro | 65 kW | 4-es besorolas |
            [
                'elofiz_azon' => 3,
                'auto_besorolas' => 4,
                'berles_ind' => 1990,
                'vez_perc' => 78,           # Csak ha a 24 órán túl (napi bérlés minimum! túlmegy akkor pluszban.)
                'kedv_vez' => null,
                'parkolas_perc' => 78,
                'foglalasi_perc' => null,
                'kedv_parkolas_perc' => null,
                'napidij' => 22680,
                'hetvegi_napi_dij' => null,
                'napi_km_limit' => 100,
                'km_dij' => 48,
                'repter_ki_felar' => null,
                'repter_be_felar' => null,
                'repter_ki_terminal' => null,
                'repter_be_terminal' => null,
                'zona_nyit_felar' => null,
                'zona_zar_felar' => null,
                'harom_ora_dij' => null,
                'hat_ora_dij' => null,
                'tizenketto_ora_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            # Opel Vivaro | 75 kW | 5-ös besorolas |
            [
                'elofiz_azon' => 3,
                'auto_besorolas' => 5,
                'berles_ind' => 4500,
                'vez_perc' => 78,           # Csak ha a 24 órán túl (napi bérlés minimum! túlmegy akkor pluszban.)
                'kedv_vez' => null,
                'parkolas_perc' => 78,
                'foglalasi_perc' => null,
                'kedv_parkolas_perc' => null,
                'napidij' => 20128,
                'napi_km_limit' => 15,
                'km_dij' => 48,
                'repter_ki_felar' => null,
                'repter_be_felar' => null,
                'repter_ki_terminal' => null,
                'repter_be_terminal' => null,
                'zona_nyit_felar' => null,
                'zona_zar_felar' => null,
                'harom_ora_dij' => 11070,
                'hat_ora_dij' => 14090,
                'tizenketto_ora_dij' => 17109,
                'hetvegi_napi_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            ###################################
            ## [Power-VIP] árak * [5 kategoria]
            # E-up | 18 kW | 1-as besorolas |
            [
                'elofiz_azon' => 4,
                'auto_besorolas' => 1,
                'berles_ind' => 250,
                'vez_perc' => 58,
                'kedv_vez' => 50,
                'parkolas_perc' => 41,
                'foglalasi_perc' => 41,
                'kedv_parkolas_perc' => 24,
                'napidij' => 13680,
                'hetvegi_napi_dij' => null,
                'napi_km_limit' => 125,
                'km_dij' => 48,
                'repter_ki_felar' => 1245,
                'repter_be_felar' => 495,
                'repter_ki_terminal' => 2490,
                'repter_be_terminal' => 990,
                'zona_nyit_felar' => 0,
                'zona_zar_felar' => 0,
                'harom_ora_dij' => null,
                'hat_ora_dij' => null,
                'tizenketto_ora_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            # Renault Kangoo - 33 kW
            [
                'elofiz_azon' => 4,
                'auto_besorolas' => 2,
                'berles_ind' => 250,
                'vez_perc' => 58,
                'kedv_vez' => null,
                'parkolas_perc' => 41,
                'foglalasi_perc' => 41,
                'kedv_parkolas_perc' => 24,
                'napidij' => 19680,
                'hetvegi_napi_dij' => null,
                'napi_km_limit' => 125,
                'km_dij' => 48,
                'repter_ki_felar' => null,
                'repter_be_felar' => null,
                'repter_ki_terminal' => null,
                'repter_be_terminal' => null,
                'zona_nyit_felar' => null,
                'zona_zar_felar' => null,
                'harom_ora_dij' => null,
                'hat_ora_dij' => null,
                'tizenketto_ora_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            # E-up, Citigo | 36kW | 3-as besorolas |
            [
                'elofiz_azon' => 4,
                'auto_besorolas' => 3,
                'berles_ind' => 250,
                'vez_perc' => 58,
                'kedv_vez' => 50,
                'parkolas_perc' => 41,
                'foglalasi_perc' => 41,
                'kedv_parkolas_perc' => 24,
                'napidij' => 14680,
                'hetvegi_napi_dij' => null,
                'napi_km_limit' => 125,
                'km_dij' => 48,
                'repter_ki_felar' => 1245,
                'repter_be_felar' => 495,
                'repter_ki_terminal' => 2490,
                'repter_be_terminal' => 990,
                'zona_nyit_felar' => 0,
                'zona_zar_felar' => 0,
                'harom_ora_dij' => null,
                'hat_ora_dij' => null,
                'tizenketto_ora_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            # Kia Niro | 65 kW | 4-es besorolas |
            [
                # 22670
                'elofiz_azon' => 4,
                'auto_besorolas' => 4,
                'berles_ind' => 1990,
                'vez_perc' => 78,           # Csak ha a 24 órán túl (napi bérlés minimum! túlmegy akkor pluszban.)
                'kedv_vez' => null,
                'parkolas_perc' => null,
                'foglalasi_perc' => null,
                'kedv_parkolas_perc' => null,
                'napidij' => 20680,
                'hetvegi_napi_dij' => null,
                'napi_km_limit' => 125,
                'km_dij' => 48,
                'repter_ki_felar' => null,
                'repter_be_felar' => null,
                'repter_ki_terminal' => null,
                'repter_be_terminal' => null,
                'zona_nyit_felar' => null,
                'zona_zar_felar' => null,
                'harom_ora_dij' => null,
                'hat_ora_dij' => null,
                'tizenketto_ora_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            # Opel Vivaro | 75 kW | 5-ös besorolas |
            [
                'elofiz_azon' => 4,
                'auto_besorolas' => 5,
                'berles_ind' => 4500,
                'vez_perc' => 78,           # Csak ha a 24 órán túl (napi bérlés minimum! túlmegy akkor pluszban.)
                'kedv_vez' => null,
                'parkolas_perc' => null,
                'foglalasi_perc' => null,
                'kedv_parkolas_perc' => null,
                'napidij' => 20128,
                'napi_km_limit' => 15,
                'km_dij' => 48,
                'repter_ki_felar' => null,
                'repter_be_felar' => null,
                'repter_ki_terminal' => null,
                'repter_be_terminal' => null,
                'zona_nyit_felar' => null,
                'zona_zar_felar' => null,
                'harom_ora_dij' => 10603,
                'hat_ora_dij' => 13495,
                'hetvegi_napi_dij' => null,
                'tizenketto_ora_dij' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
