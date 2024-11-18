<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\SeederHelperek\FelhasznalokSeederHelper;

class FelhasznalokSeeder extends Seeder
{
    public function run(): void
    {
        $helper = new FelhasznalokSeederHelper();
        $szemelyek = DB::table('szemelyek')->get();

        foreach ($szemelyek as $szemely) {
            $jelszo24 = $helper->generateJelszo24($szemely->felh_jelszo);

            DB::table('felhasznalok')->insert([
                'szemely_id_FK' => $szemely->szemely_id,
                'felh_egyenleg' => 0, // Alapértelmezett érték
                'jelszo_2_4' => $jelszo24,
                'felh_nev' => $helper->felhasznaloNevGenerator($szemely->v_nev),
                'elofiz_kat' => $helper->getRandomElofizKategoria(),
            ]);
        }
    }
}
