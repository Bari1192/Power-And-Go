<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\SeederHelperek\AutokSeederHelper;

class AutokSeeder extends Seeder
{
    private array $rendszamok = [];

    public function run(): void
    {
        $autohelper = new AutokSeederHelper();

        for ($i = 1; $i <= 200; $i++) {
            $rendszam = $autohelper->generateRendszam($this->rendszamok);
            $gyartasiEv = random_int(2019, 2023);
            $kmOraAllas = $autohelper->kmOraAllasGeneralas($gyartasiEv);
            $flottaId = $autohelper->generateFlottaId();

            DB::table('autok')->insert([
                'flotta_id' => $flottaId,
                'rendszam' => $rendszam,
                'gyartasi_ev' => $gyartasiEv,
                'km_ora_allas' => $kmOraAllas,
            ]);
        }
    }
}
