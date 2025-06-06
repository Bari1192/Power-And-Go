<?php

namespace Database\Factories;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Fleet;
use Illuminate\Support\Facades\DB;

class CarFactory extends Factory
{
    private static array $generaltRendszamok = [];

    public function definition(): array
    {
        $gyartasiEv = fake()->numberBetween(2019, 2023);
        $flottaId = $this->attributes['fleet_id'] ?? $this->flottabolAutotIdAlapjan();
        $categoryId = $this->attributes['category_id'] ?? null;
        $flottacarmodel = Fleet::find($flottaId);

        if ($categoryId === null) {
            $categoryId = $this->katBesorolasAutomatan($flottaId);
        }

        $equipment_class = Equipment::inRandomOrder()->first();

        $toltes_szazalek = fake()->numberBetween(35, 100);
        $power_kw = round($flottacarmodel->motor_power * ($toltes_szazalek / 100), 1);
        $becsultdriving_range = round(($flottacarmodel->driving_range * $toltes_szazalek) / 100, 1);

        return [
            'plate' => $this->rendszamGeneralasUjRegi(),
            'power_percent' => $toltes_szazalek,
            'power_kw' => $power_kw,
            'estimated_range' => $becsultdriving_range,
            'fleet_id' => $flottacarmodel->id,
            'category_id' => $categoryId,
            'manufactured' => $gyartasiEv,
            'odometer' => $this->kmOraAllasGeneralas($gyartasiEv),
            'equipment_class' => $equipment_class ? $equipment_class->id : 1,
            'status' => 1,
        ];
    }
    private function flottabolAutotIdAlapjan(): int
    {
        $random = mt_rand(1, 100);

        if ($random <= 50) {
            return 1;
        } elseif ($random <= 93) {
            return 3;
        } elseif ($random <= 96) {
            return 4;
        } elseif ($random <= 98) {
            return 5;
        } else {
            return 2;
        }
    }
    private function katBesorolasAutomatan(int $flotta): int
    {
        $idAlapjanKatBesorolas = DB::table('fleets')->where('id', $flotta)->first();
        if (!$idAlapjanKatBesorolas) {
            throw new \Exception("Flotta nem található az ID alapján: $flotta");
        }

        return match ($idAlapjanKatBesorolas->motor_power) {
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
        $maxAttempts = 10;
        $attempt = 0;
        do {
            $attempt++;
            if (random_int(0, 1) === 1) {
                ## Új típusú rendszám (pl.: ABC-123)
                $betuk = chr(rand(65, 65 + 25)); ## A-Z
                $masodikBetuk = chr(rand(65, 65 + 25)); ## A-Z
                $plate = 'AA' . $betuk . $masodikBetuk . '-' . str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);
            } else {
                ## Régi típusú rendszám
                $elsoBetuK = ['M', 'N', 'P', 'R', 'S', 'T'];
                $elsoBetu = $elsoBetuK[array_rand($elsoBetuK)];
                $masodikBetu = chr(rand(65, 90)); ## A-Z
                $harmadikBetu = chr(rand(65, 90)); ## A-Z
                $plate = $elsoBetu . $masodikBetu . $harmadikBetu . '-' . str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);
            }
            if ($attempt >= $maxAttempts) {
                $plate .= '-' . uniqid();
            }
        } while (isset(self::$generaltRendszamok[$plate]) && $attempt < $maxAttempts);
        self::$generaltRendszamok[$plate] = true;
        return $plate;
    }

    public function kmOraAllasGeneralas(int $gyartasiEv): int
    {
        return match ($gyartasiEv) {
            2019 => random_int(50000, 60000),
            2020 => random_int(40000, 60000),
            2021 => random_int(30000, 40000),
            2022 => random_int(25000, 35000),
            2023 => random_int(20000, 30000),
            default => 0, # Szalonból hozott autó
        };
    }
}
