<?php

namespace Database\Seeders;

use App\Models\Felhasznalo;
use App\Models\LezartBerles;
use App\Models\Szamla;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SzamlaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mindenLezartBerles = LezartBerles::all();

        foreach ($mindenLezartBerles as $egySzamla) {
            $felhasznalo = Felhasznalo::where('szemely_id', '=', $egySzamla->szemely_id_fk)->first();
            Szamla::create([
                'szamla_tipus' => 'berles',
                'felh_id' => $felhasznalo->felh_id,
                'szemely_id' => $egySzamla->szemely_id_fk,
                'auto_azon' => $egySzamla->auto_azonosito,
                'berles_kezd_datum' => $egySzamla->berles_kezd_datum,
                'berles_kezd_ido' => $egySzamla->berles_kezd_ido,
                'berles_veg_datum' => $egySzamla->berles_veg_datum,
                'berles_veg_ido' => $egySzamla->berles_veg_ido,
                'megtett_tavolsag' => $egySzamla->megtett_tavolsag,
                'parkolasi_perc' => $egySzamla->parkolasi_perc,
                'vezetesi_perc' => $egySzamla->vezetesi_perc,
                'berles_osszeg' => $egySzamla->berles_osszeg,
                'szamla_kelt' => now(),
                'szamla_status' => 'pending',
            ]);
        }
    }
}
