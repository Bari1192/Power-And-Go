<?php

namespace App\Policies;

use App\Models\Car;

class CarRefreshService
{
    public array $chargingCategories = [
        1 => ['min_toltes' => 9.0, 'buntetes' => 30000],
        2 => ['min_toltes' => 6.0, 'buntetes' => 50000],
        3 => ['min_toltes' => 4.5, 'buntetes' => 30000],
        4 => ['min_toltes' => 4.0, 'buntetes' => 50000],
        5 => ['min_toltes' => 4.0, 'buntetes' => 50000],
    ];
    ## ZÁRÁS UTÁNI töltöttségi állapot vizsgálata
    public function ellenorizToltottseg(Car $car, float $toltesSzazalek): array
    {
        # Ha uj autó kerül felvételre, VAGY folyamatban lévő bérlésről van szó!
        if (!isset($this->chargingCategories[$car->category_id]) || $car->status == 3) {
            return ['buntetendo' => false];
        }
        $minToltes = round($this->chargingCategories[$car->category_id]['min_toltes'], 1);

        ## BÜNTETÉS
        if ($toltesSzazalek < $minToltes) {
            $car->status = 7;
            $car->save();
            return [
                'buntetendo' => true,
                'buntetes_osszeg' => $this->chargingCategories[$car->category_id]['buntetes'],
                'aktualis_toltes' => $toltesSzazalek,
            ];
        }
        if ($toltesSzazalek >= $minToltes && $toltesSzazalek <= 15.0) {
            $car->status = 7;
            $car->save();
            return [
                'buntetendo' => false,
                'aktualis_toltes' => $toltesSzazalek,
            ];
        }
        $car->status = 1;
        $car->save();
        return ['buntetendo' => false];
    }
    ## Töltés utáni autó adatok frissítése.
    public function frissitesToltesUtan(Car $car, float $ujToltesSzazalek, float $ujToltesKw): void
    {
        $car->power_percent = min(100, max(0, $ujToltesSzazalek));
        $car->power_kw = min($car->fleet->motor_power, max(0, $ujToltesKw));
        $car->estimated_range = round(($car->fleet->driving_range / 100) * $car->power_percent);
        $car->save();
    }
    public function frissitesTavolsagUtan(Car $car, int $megtettTavolsag): array
    {
        $egyKwKilometerben = $car->fleet->driving_range / $car->fleet->motor_power;
        $kwFogyasztas = round($megtettTavolsag / $egyKwKilometerben, 1);

        $minKw = round($car->fleet->motor_power * 0.1, 1); // 1% minimum
        $ujToltesKw = round(max($minKw, $car->power_kw - $kwFogyasztas), 1);
        $ujToltesSzazalek = round(($ujToltesKw / $car->fleet->motor_power) * 100, 2);

        $car->power_kw = $ujToltesKw;
        $car->power_percent = $ujToltesSzazalek;
        $car->estimated_range = round(($car->fleet->driving_range / 100) * $ujToltesSzazalek, 1);
        $car->odometer += $megtettTavolsag;
        $car->save();

        return [
            'uj_toltes_szazalek' => $ujToltesSzazalek,
            'uj_toltes_kw' => $ujToltesKw,
            'fogyasztas_kw' => $kwFogyasztas
        ];
    }
}
