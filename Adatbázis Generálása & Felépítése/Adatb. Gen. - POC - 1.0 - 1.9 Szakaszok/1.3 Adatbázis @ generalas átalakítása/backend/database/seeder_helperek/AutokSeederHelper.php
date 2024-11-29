<?php

namespace Database\SeederHelperek;

use Faker\Factory as Faker;

class AutokSeederHelper
{
    private array $rendszamok = [];

    public function generateFlottaId(): int
    {
        $autokAranyszama = rand(1, 100);

        if ($autokAranyszama <= 85) {
            return rand(1, 3);
        } elseif ($autokAranyszama > 85 && $autokAranyszama <= 90) {
            return 4;
        } elseif ($autokAranyszama > 90 && $autokAranyszama <= 95) {
            return 5;
        } else {
            return 6;
        }
    }
    public function generateRendszam(array &$existingRendszamok): string
    {
        do {
            $rendszamUjRegi = random_int(0, 1);
            if ($rendszamUjRegi > 0) {
                $rendszam = strtoupper(Faker::create()->regexify('AA[A-C][A-O]-[0-9]{3}'));
            } else {
                $rendszam = strtoupper(Faker::create()->regexify('(M|N|P|R|S|T)[A-Z]{2}-[0-9]{3}'));
            }
        } while (in_array($rendszam, $existingRendszamok));

        $existingRendszamok[] = $rendszam;

        return $rendszam;
    }
    public function kmOraAllasGeneralas(int $gyartasiEv): int
    {
        switch ($gyartasiEv) {
            case 2019:
                return random_int(50000, 60000);
            case 2020:
                return random_int(40000, 60000);
            case 2021:
                return random_int(30000, 40000);
            case 2022:
                return random_int(25000, 35000);
            case 2023:
                return random_int(20000, 30000);
            default:
                return 0;
        }
    }
}
