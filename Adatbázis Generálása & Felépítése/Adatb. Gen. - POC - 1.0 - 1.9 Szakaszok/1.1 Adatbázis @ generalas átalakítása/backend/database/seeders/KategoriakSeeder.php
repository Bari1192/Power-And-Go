<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\SeederHelperek\KategoriakSeederHelper;
use Illuminate\Support\Facades\DB; 

class KategoriakSeeder extends Seeder
{
    public function run(): void
    {
        $helper = new KategoriakSeederHelper();
        $autok = DB::table('autok')->get();

        foreach ($autok as $auto) {
            try {
                $katBesorolas = $helper->generateKatBesorolas($auto->rendszam);
                DB::table('kategoriak')->insert([
                    'rendszam' => $auto->rendszam,
                    'kat_besorolas' => $katBesorolas,
                ]);
            } catch (\Exception $e) {
                echo "Hiba: {$e->getMessage()}\n";
            }
        }
    }
}
