<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\SeederHelperek\FelszereltsegekSeederHelper;

class FelszereltsegekSeeder extends Seeder
{
    public function run(): void
    {
        $helper = new FelszereltsegekSeederHelper();
        $kategoriak = DB::table('kategoriak')->get();
        foreach ($kategoriak as $kategoria) {
            $rendszam = $kategoria->rendszam;
            $katBesorolas = $kategoria->kat_besorolas;
            $felszerelesek = $helper->generateFelszerelesek($katBesorolas);

            DB::table('felszereltsegek')->insert(array_merge(
                ['rendszam' => $rendszam],
                $felszerelesek
            ));
        }
    }
}
