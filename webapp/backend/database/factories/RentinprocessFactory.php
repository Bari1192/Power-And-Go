<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\Rentinprocess;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RentinprocessFactory extends Factory
{
    protected $model = Rentinprocess::class;

    // Státusz konstansok
    private const CAR_STATUS_SZABAD = 1;
    private const CAR_STATUS_BERLES_ALATT = 3;
    private const RENT_STATUS_FOLYAMATBAN = 3;

    private array $usedRendszamok = [];
    private array $usedFelhasznalok = [];

    public function definition(): array
    {
        // Csak szabad autót keresünk, ami még nem volt használva
        $car = Car::with('fleet')  // fleet kapcsolat a motor_power miatt kell
            ->where('status', 1)
            ->whereNotIn('plate', $this->usedRendszamok)
            ->inRandomOrder()
            ->first();

        if (!$car) {
            throw new \RuntimeException('Nincs több elérhető szabad autó a folyamatban lévő bérlések generálásához.');
        }

        $this->usedRendszamok[] = $car->plate;

        // Felhasználó lekérése, aki még nem volt használva
        $user = User::whereNotIn('id', $this->usedFelhasznalok)
            ->inRandomOrder()
            ->first();

        if (!$user) {
            throw new \RuntimeException('Nincs több elérhető felhasználó a folyamatban lévő bérlések generálásához.');
        }

        $this->usedFelhasznalok[] = $user->id;

        // Bérlés kezdési idő generálása
        $veletlenSzam = random_int(1, 100);
        $idoKezdet = match (true) {
            $veletlenSzam <= 5 => '-72 hours',
            $veletlenSzam <= 15 => '-24 hours',
            $veletlenSzam <= 30 => '-18 hours',
            $veletlenSzam <= 45 => '-8 hours',
            $veletlenSzam <= 65 => '-4 hours',
            default => '-2 hours',
        };
        $berlesKezdete = now()->modify($idoKezdet);

        // Kezdeti értékek az autóból
        $nyitasToltesSzazalek = $car->power_percent;
        $nyitasToltesKw = round($car->fleet->motor_power * ($nyitasToltesSzazalek / 100), 1);

        // Az autó státuszának frissítése bérlés alatt-ra
        $car->status = 3;
        $car->save();

        return [
            'car_id' => $car->id,
            'category_id' => $car->category_id,
            'user_id' => $user->id,
            'rent_start' => $berlesKezdete,
            'start_percent' => $nyitasToltesSzazalek,
            'start_kw' => $nyitasToltesKw,
            'invoice_date' => now(),
            'rentstatus' => 3,
        ];
    }
}
