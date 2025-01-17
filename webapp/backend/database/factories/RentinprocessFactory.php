<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\Rentinprocess;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RentinprocessFactory extends Factory
{
    protected $model = Rentinprocess::class;

    private array $usedRendszamok = [];
    private array $usedFelhasznalok = [];
    public function definition(): array
    {
        // Rendszám lekérése
        $car = Car::whereNotIn('plate', $this->usedRendszamok)->inRandomOrder()->first();
        $this->usedRendszamok[] = $car->plate;

        // Felhasználó lekérése
        $felhasznalo = User::whereNotIn('person_id', $this->usedFelhasznalok)->inRandomOrder()->first();
        $this->usedFelhasznalok[] = $felhasznalo->person_id;

        // Bérlés kezdési idő generálása
        $veletlenSzam = random_int(1, 100);

        if ($veletlenSzam <= 5) {
            $idoKezdet = '-72 hours';
        } elseif ($veletlenSzam <= 15) {
            $idoKezdet = '-24 hours';
        } elseif ($veletlenSzam <= 30) {
            $idoKezdet = '-18 hours';
        } elseif ($veletlenSzam <= 45) {
            $idoKezdet = '-8 hours';
        } elseif ($veletlenSzam <= 65) {
            $idoKezdet = '-4 hours';
        } else {
            $idoKezdet = '-2 hours';
        }
        $berlesKezdete = now()->modify($idoKezdet);

        $nyitasToltesSzazalek = $car->power_percent;
        $nyitasToltesKw = round($car->fleet->motor_power * ($nyitasToltesSzazalek / 100), 1);
        return [
            'car_id' => $car->id,
            'category_id' => $car->category_id,
            'user_id' => $felhasznalo->person_id,
            'rent_start_date' => $berlesKezdete->format('Y-m-d'),
            'rent_start_time' => $berlesKezdete->format('H:i:s'),
            'start_percent' => $nyitasToltesSzazalek,
            'start_kw' => $nyitasToltesKw,
            'invoice_date'=>now(),
            'rentstatus'=>3, // folyamatban -> carstatus alapján
        ];
    }
}
