<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\SeederHelperek\LezartBerlesekSeederHelper;

class LezartBerlesekSeeder extends Seeder
{
    public function run(): void
    {
        $helper = new LezartBerlesekSeederHelper();

        $autok = DB::table('autok')->get()->toArray(); 
        $kategoriak = DB::table('kategoriak')->pluck('kat_besorolas', 'rendszam')->toArray();
        $felhasznalok = DB::table('felhasznalok')->get()->toArray();

        $berlesek = $helper->generateBerlesek($autok, $kategoriak, $felhasznalok, 200);

        DB::table('lezart_berlesek')->insert($berlesek);
    }
}
