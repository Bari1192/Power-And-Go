<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\SeederHelperek\FutoBerlesekSeederHelper;

class FutoBerlesekSeeder extends Seeder
{
    public function run(): void
    {
        $helper = new FutoBerlesekSeederHelper();

        $autok = DB::table('autok')->get()->map(fn($auto) => (array)$auto)->toArray();
        $kategoriak = DB::table('kategoriak')->pluck('kat_besorolas', 'rendszam')->toArray();
        $felhasznalok = DB::table('felhasznalok')->get()->map(fn($felhasznalo) => (array)$felhasznalo)->toArray();

        // Futó bérlések generálása
        $berlesek = $helper->generateFutoBerlesek($autok, $kategoriak, $felhasznalok, count($felhasznalok));
        DB::table('futo_berlesek')->insert($berlesek);
    }
}
