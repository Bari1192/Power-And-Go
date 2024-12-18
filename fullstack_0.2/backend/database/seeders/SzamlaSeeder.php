<?php

namespace Database\Seeders;

use App\Models\Felhasznalo;
use App\Models\LezartBerles;
use App\Models\Szamla;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SzamlaSeeder extends Seeder
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

        ###     Normál számla gyártás   ###
        $mindenLezartBerles = LezartBerles::all();

        foreach ($mindenLezartBerles as $egyBerles) {
            $felhasznalo = Felhasznalo::where('szemely_id', $egyBerles->szemely_azon)->first();
            Szamla::create([
                'szamla_tipus' => 'berles',
                'felh_id' => $felhasznalo->felh_id,
                'szemely_id' => $egyBerles->szemely_azon,
                'auto_azon' => $egyBerles->auto_azon,
                'berles_kezd_datum' => $egyBerles->berles_kezd_datum,
                'berles_kezd_ido' => $egyBerles->berles_kezd_ido,
                'berles_veg_datum' => $egyBerles->berles_veg_datum,
                'berles_veg_ido' => $egyBerles->berles_veg_ido,
                'megtett_tavolsag' => $egyBerles->megtett_tavolsag,
                'parkolasi_perc' => $egyBerles->parkolasi_perc,
                'vezetesi_perc' => $egyBerles->vezetesi_perc,
                'osszeg' => $egyBerles->berles_osszeg,
                'szamla_kelt' =>now(),
                'szamla_status' => 'pending',
            ]);

            # Büntetési számla alkalmazása
            $autoKat = $egyBerles->auto_kat;
            $zarasToltesSzazalek = $egyBerles->zaras_szaz;

            ### NE FELEDD!!!  A BÜNTETÉS KIÁLLÍTÁSI TÖLTÉSI % ALAPJÁN (pl: 4-6-10% alatt bünti)
            ### ELTÉR ATTÓL ahogyan az autó ZÁRÁS UTÁN 6-OS STÁTUSZRA ÁLLÍTJUK! (státusz 15% alatt ÁLL ÁT!!!)
            if (isset($kategoriak[$autoKat]) && $zarasToltesSzazalek < $kategoriak[$autoKat]['min_toltes']) {
                $buntetesAdatok[] = [
                    'szamla_tipus' => 'toltes_buntetes',
                    'felh_id' => $felhasznalo->felh_id,
                    'szemely_id' => $egyBerles->szemely_azon,
                    'auto_azon' => $egyBerles->auto_azon,
                    'berles_kezd_datum' => $egyBerles->berles_kezd_datum,
                    'berles_kezd_ido' => $egyBerles->berles_kezd_ido,
                    'berles_veg_datum' => $egyBerles->berles_veg_datum,
                    'berles_veg_ido' => $egyBerles->berles_veg_ido,
                    'megtett_tavolsag' => $egyBerles->megtett_tavolsag,
                    'parkolasi_perc' => $egyBerles->parkolasi_perc,
                    'vezetesi_perc' => $egyBerles->vezetesi_perc,
                    'osszeg' => $kategoriak[$autoKat]['buntetes'],
                    'szamla_kelt' =>now(),
                    'szamla_status' => 'pending',
                ];
            }
        }

        ## Tömeges adatbeszúrás || Amennyiben van számla, amiből generálhat,
        ## Akkor készíteni fog addig.
        if (!empty($szamlaAdatok)) {
            DB::table('szamlak')->insert($szamlaAdatok);
        }

        if (!empty($buntetesAdatok)) {
            DB::table('szamlak')->insert($buntetesAdatok);
        }
    }
}
