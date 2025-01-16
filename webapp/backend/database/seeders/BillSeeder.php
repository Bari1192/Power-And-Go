<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Car;
use App\Models\Renthistory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ## [ SZABÁLYZAT ] ##
        $kategoriak = [
            1 => ['min_toltes' => 9.0, 'buntetes' => 30000],
            2 => ['min_toltes' => 6.0, 'buntetes' => 50000],
            3 => ['min_toltes' => 4.5, 'buntetes' => 30000],
            4 => ['min_toltes' => 4.0, 'buntetes' => 50000],
            5 => ['min_toltes' => 4.0, 'buntetes' => 50000],
        ];
        # 1-es autó besorolas [E-up - 18kw] típus
        # 2-es besorolas [Renault Kangoo - 33 kW] típus
        # 3-as besorolas [Citigo & E-up - 36 kW] típus
        # 4-es besorolas [Kia Niro - 65 kW] típus
        # 5-ös besorolas [ Opel Vivaro - 75 kW] típus

        $lezartBerlesek = DB::table('car_user_rents')
            ->where('rentstatus', 2) // Csak a lezárt bérlések
            ->get();

        $szamlaAdatok = [];
        $buntetesAdatok = [];

        foreach ($lezartBerlesek as $berles) {
            // Lekérjük a user és car adatokat a kapcsolatokból
            $felhasznalo = User::find($berles->user_id);
            $auto = Car::find($berles->car_id);

            // Számla adatok generálása
            $szamlaAdatok[] = [
                'szamla_tipus' => 'berles',
                'felh_id' => $felhasznalo->id,
                'person_id' => $felhasznalo->person_id,
                'car_id' => $auto->id,
                'berles_kezd_datum' => $berles->berles_kezd_datum,
                'berles_kezd_ido' => $berles->berles_kezd_ido,
                'berles_veg_datum' => $berles->berles_veg_datum,
                'berles_veg_ido' => $berles->berles_veg_ido,
                'megtett_tavolsag' => $berles->megtett_tavolsag,
                'parkolasi_perc' => $berles->parkolasi_perc,
                'vezetesi_perc' => $berles->vezetesi_perc,
                'osszeg' => $berles->berles_osszeg,
                'szamla_kelt' => now(),
                'szamla_status' => 'pending',
            ];

            // Büntetés generálása a töltöttségi szint alapján
            $kategoriak = [
                1 => ['min_toltes' => 9.0, 'buntetes' => 30000],
                2 => ['min_toltes' => 6.0, 'buntetes' => 50000],
                3 => ['min_toltes' => 4.5, 'buntetes' => 30000],
                4 => ['min_toltes' => 4.0, 'buntetes' => 50000],
                5 => ['min_toltes' => 4.0, 'buntetes' => 50000],
            ];

            $autoKategoria = $berles->kategoria;
            $zarasToltesSzazalek = $berles->zaras_szaz;

            if (isset($kategoriak[$autoKategoria]) && $zarasToltesSzazalek < $kategoriak[$autoKategoria]['min_toltes']) {
                $buntetesAdatok[] = [
                    'szamla_tipus' => 'toltes_buntetes',
                    'felh_id' => $felhasznalo->id,
                    'person_id' => $felhasznalo->person_id,
                    'car_id' => $auto->id,
                    'berles_kezd_datum' => $berles->berles_kezd_datum,
                    'berles_kezd_ido' => $berles->berles_kezd_ido,
                    'berles_veg_datum' => $berles->berles_veg_datum,
                    'berles_veg_ido' => $berles->berles_veg_ido,
                    'megtett_tavolsag' => $berles->megtett_tavolsag,
                    'parkolasi_perc' => $berles->parkolasi_perc,
                    'vezetesi_perc' => $berles->vezetesi_perc,
                    'osszeg' => $kategoriak[$autoKategoria]['buntetes'],
                    'szamla_kelt' => now(),
                    'szamla_status' => 'pending',
                ];
            }
        }

        // Adatok mentése az adatbázisba
        if (!empty($szamlaAdatok)) {
            DB::table('bills')->insert($szamlaAdatok);
        }

        if (!empty($buntetesAdatok)) {
            DB::table('bills')->insert($buntetesAdatok);
        }
    }
}
