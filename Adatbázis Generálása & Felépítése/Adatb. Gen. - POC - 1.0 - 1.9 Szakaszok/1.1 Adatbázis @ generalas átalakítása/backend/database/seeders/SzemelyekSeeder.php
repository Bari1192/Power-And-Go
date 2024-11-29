<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\SeederHelperek\SzemelyekSeederHelper;


class SzemelyekSeeder extends Seeder
{
    public function run(): void
    {
        $helper = new SzemelyekSeederHelper();

        # ITT is 200 DB személyt generálok!
        for ($i = 1; $i <= 200; $i++) {
            $nev = $helper->generateNev();
            $jogsiDatumok = $helper->generateJogsiDatumok();

            DB::table('szemelyek')->insert([
                'v_nev' => $nev['v_nev'],
                'k_nev' => $nev['k_nev'],
                'szul_datum' => $nev['szul_datum']->format('Y-m-d'),
                'telefon' => $helper->generateTelefon(),
                'email' => $helper->generateEmail(),
                'szig_szam' => $helper->generateSzemelyiIgazolvany(),
                'jogos_szam' => $helper->generateSzemelyiIgazolvany(),
                'jogos_erv_kezdete' => $jogsiDatumok['jogos_erv_kezdete']->format('Y-m-d'),
                'jogos_erv_vege' => $jogsiDatumok['jogos_erv_vege']->format('Y-m-d'),
                'felh_jelszo' => $helper->generateFelhJelszo(),
            ]);
        }
    }
}
