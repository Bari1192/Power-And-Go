<?php

namespace Database\SeederHelperek;
use Illuminate\Support\Facades\DB;

class KategoriakSeederHelper
{
    public function generateKatBesorolas(string $rendszam): int
    {
        $auto = DB::table('autok')->where('rendszam', $rendszam)->first();
        if (!$auto) {
            throw new \Exception("Hiba: Nem található autó a rendszámmal: $rendszam");
        }
        $flotta = DB::table('flotta_tipusok')->where('flotta_id', $auto->flotta_id)->first();
        if (!$flotta) {
            throw new \Exception("Hiba: Nem található flotta a flotta_id-val: {$auto->flotta_id}");
        }
        return match ($flotta->teljesitmeny) {
            18 => 1,
            36 => 2,
            45 => 3,
            50 => 4,
            65 => 5,
            default => 5,
        };
    }
}