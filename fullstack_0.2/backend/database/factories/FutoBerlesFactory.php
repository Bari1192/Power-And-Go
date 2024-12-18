<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\FutoBerles;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FutoBerlesFactory extends Factory
{
    protected $model = FutoBerles::class;

    private array $usedRendszamok = [];
    private array $usedFelhasznalok = [];
    public function definition(): array
    {
        // Rendszám lekérése
        $car = Car::whereNotIn('rendszam', $this->usedRendszamok)->inRandomOrder()->first();
        $this->usedRendszamok[] = $car->rendszam;

        // Felhasználó lekérése
        $felhasznalo = User::whereNotIn('szemely_id', $this->usedFelhasznalok)->inRandomOrder()->first();
        $this->usedFelhasznalok[] = $felhasznalo->szemely_id;

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

        return [
            'auto_azon' => $car->id,
            'kategoria' => $car->kategoria,
            'szemely_azon' => $felhasznalo->szemely_id,
            'berles_kezd_datum' => $berlesKezdete->format('Y-m-d'),
            'berles_kezd_ido' => $berlesKezdete->format('H:i:s'),
        ];
    }
}
