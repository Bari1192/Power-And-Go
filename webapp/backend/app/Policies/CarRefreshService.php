<?php

namespace App\Policies;

use App\Models\Car;

class CarRefreshService
{
    public array $chargingCategories = [
        1 => ['min_toltes' => 9.0],
        2 => ['min_toltes' => 6.0],
        3 => ['min_toltes' => 4.5],
        4 => ['min_toltes' => 4.0],
        5 => ['min_toltes' => 4.0],
    ];
    ## ZÁRÁS UTÁNI töltöttségi állapot vizsgálata
    public function ellenorizToltottseg(Car $car, float $toltesSzazalek): array
    {
        # Ha uj autó kerül felvételre, VAGY folyamatban lévő bérlésről van szó!
        if (!isset($this->chargingCategories[$car->category_id]) || $car->status == 3) {
            return ['buntetendo' => false];
        }
        ## BÜNTETÉS
        if ($toltesSzazalek < $this->chargingCategories[$car->category_id]['min_toltes']) {
            $car->status = 7;
            $car->save();
            return [
                'buntetendo' => true,
                'aktualis_toltes' => $toltesSzazalek,
            ];

            ## Rendszer kivét
        } elseif ($toltesSzazalek >= $this->chargingCategories[$car->category_id]['min_toltes'] && $toltesSzazalek <= 15.0) {
            $car->status = 7;
            $car->save();
            return [
                'buntetendo' => false,
                'aktualis_toltes' => $toltesSzazalek,
            ];
            ## Visszadob
        } else {
            $car->status = 1;
            $car->save();
            return [
                'buntetendo' => false,
                'aktualis_toltes' => $toltesSzazalek,
            ];
        }
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

         ## Random minimum érték | 1-15% között | a töltöttséghez
        $minPercent = mt_rand(1, 15) / 100; 
        $minKw = round($car->fleet->motor_power * $minPercent, 2);

        ## Korlátozások és frissítések!
        $ujToltesKw = round(max($minKw, $car->power_kw - $kwFogyasztas), 2);
        $ujToltesKw = min($car->fleet->motor_power, $ujToltesKw);
        $ujToltesSzazalek = round(($ujToltesKw / $car->fleet->motor_power) * 100, 2);
        $ujToltesSzazalek = min(100, max(0, $ujToltesSzazalek));
        
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
