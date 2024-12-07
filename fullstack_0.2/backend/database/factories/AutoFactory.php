<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Felszereltseg;
use App\Models\Flotta_tipusok;
use Illuminate\Support\Facades\DB;

class AutoFactory extends Factory
{
    public function definition(): array
    {
        $gyartasiEv = fake()->numberBetween(2019, 2023);
        $flotta = $this->flottabolAutotIdAlapjan();
        $felszereltseg = Felszereltseg::inRandomOrder()->first(); # Véletlenszerű felszereltség "belegenerálás"
        $flottaTipus = Flotta_tipusok::inRandomOrder()->first();

        $toltes_szazalek = fake()->randomFloat(2, 15, 100);
        $toltes_kw = round($flottaTipus->teljesitmeny * ($toltes_szazalek / 100), 1);
        $egyKilowattHanyKm = round($flottaTipus->teljesitmeny * ($toltes_kw / 100), 1);
        $becsultHatotav = round(($flottaTipus->hatotav / $flottaTipus->teljesitmeny) * $toltes_kw, 1);
        return [
            'flotta_id_fk' => $flotta,
            'kategoria_besorolas_fk' => $this->katBesorolasAutomatan($flotta),
            'rendszam' => $this->rendszamGeneralasUjRegi(),
            'gyartasi_ev' => $gyartasiEv,
            'km_ora_allas' => $this->kmOraAllasGeneralas($gyartasiEv),
            'felsz_id_fk' => $felszereltseg ? $felszereltseg->felsz_id : 1, # Kapcsolt felszereltség
            'toltes_szazalek' => $toltes_szazalek,
            'toltes_kw' => $egyKilowattHanyKm,
            'becsult_hatotav' => $becsultHatotav,

        ];
    }
    private function flottabolAutotIdAlapjan(): int
    {
        $autokAranyszama = rand(1, 100);

        if ($autokAranyszama <= 85) {
            return rand(1, 2) === 1 ? 1 : 3;
        } elseif ($autokAranyszama > 85 && $autokAranyszama <= 90) {
            return 4;
        } elseif ($autokAranyszama > 90 && $autokAranyszama <= 95) {
            return 5;
        } else {
            return 6;
        }
    }
    private function katBesorolasAutomatan(int $flotta): int
    {
        $idAlapjanKatBesorolas = DB::table('flotta_tipusok')->where('flotta_id', $flotta)->first();
        if (!$idAlapjanKatBesorolas) {
            throw new \Exception("Flotta nem található az ID alapján: $flotta");
        }

        return match ($idAlapjanKatBesorolas->teljesitmeny) {
            18 => 1,
            33 => 2,
            36 => 3,
            65 => 4,
            75 => 5,
            default => 5,
        };
    }
    private function rendszamGeneralasUjRegi(): string
    {
        static $generaltRendszamok = [];
        do {
            $rendszamUjRegi = random_int(0, 1);
            if ($rendszamUjRegi > 0) {
                $rendszam = strtoupper(fake()->regexify('AA[A-C][A-O]-[0-9]{3}'));
            } else {
                $rendszam = strtoupper(fake()->regexify('(M|N|P|R|S|T)[A-Z]{2}-[0-9]{3}'));
            }
        } while (in_array($rendszam, $generaltRendszamok));
        $generaltRendszamok[] = $rendszam;
        return $rendszam;
    }
    public function kmOraAllasGeneralas(int $gyartasiEv): int
    {
        return match ($gyartasiEv) {
            2019 => random_int(50000, 60000),
            2020 => random_int(40000, 60000),
            2021 => random_int(30000, 40000),
            2022 => random_int(25000, 35000),
            2023 => random_int(20000, 30000),
            default => 0,
            # Szalonból hozott autó
        };
    }
}
