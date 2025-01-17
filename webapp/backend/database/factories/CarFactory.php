<?php

namespace Database\Factories;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Fleet;
use Illuminate\Support\Facades\DB;

class CarFactory extends Factory
{
    public function definition(): array
    {
        $gyartasiEv = fake()->numberBetween(2019, 2023);
        $flotta = $this->flottabolAutotIdAlapjan();
        $equipment_class = Equipment::inRandomOrder()->first(); # Véletlenszerű felszereltség "belegenerálás"
        $flottacarmodel = Fleet::find($flotta);
        
        $toltes_szazalek = fake()->randomFloat(2, 15, 100);
        $power_kw = round($flottacarmodel->motor_power * ($toltes_szazalek / 100), 1);
        $becsultdriving_range = round(($flottacarmodel->driving_range / $flottacarmodel->motor_power) * $power_kw, 1);
        return [
            'fleet_id' => $flottacarmodel->id,
            'category_id' => $this->katBesorolasAutomatan($flotta),
            'plate' => $this->rendszamGeneralasUjRegi(),
            'manufacturing_year' => $gyartasiEv,
            'odometer' => $this->kmOraAllasGeneralas($gyartasiEv),
            'equipment_class' => $equipment_class ? $equipment_class->id : 1,
            'power_percent' => $toltes_szazalek,
            'power_kw' => $power_kw,
            'estimated_range' => $becsultdriving_range,
            'status' => 1, 
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
        static $generaltRendszamok = [];
        do {
            $rendszamUjRegi = random_int(0, 1);
            if ($rendszamUjRegi > 0) {
                $plate = strtoupper(fake()->regexify('AA[A-C][A-O]-[0-9]{3}'));
            } else {
                $plate = strtoupper(fake()->regexify('(M|N|P|R|S|T)[A-Z]{2}-[0-9]{3}'));
            }
        } while (in_array($plate, $generaltRendszamok));
        $generaltRendszamok[] = $plate;
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
            default => 0,
            # Szalonból hozott autó
        };
    }
}
