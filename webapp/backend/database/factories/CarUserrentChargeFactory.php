<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\CarUserrentCharge;
use App\Models\User;
use App\Policies\CarRefreshService;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarUserrentChargeFactory extends Factory
{
    protected $model = CarUserrentCharge::class;
    private CarRefreshService $carRefreshService;
    public function __construct()
    {
        parent::__construct();
        $this->carRefreshService = new CarRefreshService();
    }

    public function definition(): array
    {
        return [];
    }
    public function calculateTimes(DateTime $start, DateTime $end): array
    {
        $totalSeconds = abs($end->getTimestamp() - $start->getTimestamp());
        return [
            'seconds' => $totalSeconds,
            'minutes' => floor($totalSeconds / 60),
            'hours' => floor($totalSeconds / 3600),
            'days' => floor($totalSeconds / (24 * 3600)),
            'remainingHours' => floor(($totalSeconds % (24 * 3600)) / 3600),
            'remainingMinutes' => floor(($totalSeconds % 3600) / 60)
        ];
    }

    public function kellHozzaTolteniAutot(int $berlesIdotartam, int $megtettTavolsag, Car $car): bool
    {
        // Ha az autó töltöttsége már eleve magas vagy rövid bérlés, nincs szükség töltésre
        if ($car->power_percent > 70 || $berlesIdotartam < 30) {
            return false;
        }

        $egyKwKilometerben = round(($car->fleet->driving_range / $car->fleet->motor_power), 2);
        $jelenlegiToltesKw = $car->power_kw;
        $jelenlegiToltesSzazalek = round(($jelenlegiToltesKw / $car->fleet->motor_power) * 100, 2);

        // Max megtehető táv, perc, fogyasztás a jelenlegi töltöttséggel
        $maxtavJelenlegiToltessel = $egyKwKilometerben * $jelenlegiToltesKw;
        $maxTavoPercekreValtva = $maxtavJelenlegiToltessel * 2;
        $fogyasztas = $megtettTavolsag / $egyKwKilometerben;

        // Az alábbi feltételek bármelyikének teljesülése esetén töltésre van szükség
        if (($megtettTavolsag > $maxtavJelenlegiToltessel || $berlesIdotartam > $maxTavoPercekreValtva &&
                $fogyasztas > $jelenlegiToltesKw) ||
            ($berlesIdotartam > 60 && $jelenlegiToltesSzazalek <= 35.0) ||
            ($berlesIdotartam > 180 && $jelenlegiToltesSzazalek <= 50.0)
        ) {
            return true;
        }

        // Ha egyik feltétel sem teljesül, alapértelmezetten nincs szükség töltésre
        return false;
    }

    /**
     * Töltési esemény generálása
     */
    public function generaljToltest(Car $car, User $user, DateTime $berlesKezdete, array $times): array
    {
        try {
            # 1. Töltési sebesség meghatározása kategória alapján
            $toltesSebesseg = match ($car->category_id) {
                1, 2, 3 => fake()->randomFloat(2, 0.32, 0.37),
                4 => fake()->randomFloat(2, 0.37, 0.40),
                5 => fake()->randomFloat(2, 0.51, 0.61),
                default => fake()->randomFloat(2, 0.35, 0.40),
            };

            // Kezdeti értékek rögzítése
            $start_percent = $car->power_percent;
            $start_kw = $car->power_kw;
            $chargingStart = (clone $berlesKezdete)->modify('+5 minutes');

            // Bérlés idő és vége
            $berlesIdoTartam = $times['minutes'];
            $berlesVege = (clone $berlesKezdete)->modify("+{$times['minutes']} minutes");

            # 2. Min. töltési idő
            $minimumToltesIdo = 5;

            # 3. Max. töltési idő számítása
            $maxToltesIdoSzazSzazalekra = floor(($car->fleet->motor_power - $car->power_kw) / $toltesSebesseg);

            # 4. Korlátozások a bérlési időtartam alapján
            $maxLehetségesToltesiIdo = min($berlesIdoTartam - 10, $maxToltesIdoSzazSzazalekra);

            // Nem lehet negatív vagy túl kicsi a max töltési idő
            if ($maxLehetségesToltesiIdo <= $minimumToltesIdo) {
                $maxLehetségesToltesiIdo = $minimumToltesIdo + 1;
            }

            # 5. Töltési idő generálása
            $toltesiIdo = fake()->numberBetween($minimumToltesIdo, $maxLehetségesToltesiIdo);
            $validaltToltesVege = (clone $chargingStart)->modify('+' . $toltesiIdo . ' minutes');


            # 6. Töltés kalkuláció
            $toltottKilowatt = max(0, $toltesiIdo * $toltesSebesseg);
            $ujToltesKw = min($car->fleet->motor_power, $car->power_kw + round($toltottKilowatt, 1));
            $ujToltesSzazalek = round(($ujToltesKw / $car->fleet->motor_power) * 100, 2);

            # 7. Kreditek számítása és autó frissítése
            $credits = $this->chargingCreditsReturn($user, $toltottKilowatt);
            $this->carRefreshService->frissitesToltesUtan($car, $ujToltesSzazalek, $ujToltesKw);

            return [
                'charging_start_date' => $chargingStart,
                'charging_end_date' => $validaltToltesVege,
                'charging_time' => $toltesiIdo,
                'start_percent' => $start_percent,
                'end_percent' => $ujToltesSzazalek,
                'start_kw' => $start_kw,
                'end_kw' => $ujToltesKw,
                'charged_kw' => floor($toltottKilowatt),
                'credits' => $credits,
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    public function chargingCreditsReturn(User $user, float $toltottKilowatt): int
    {
        // 6 kW alatt: 400 kredit/kW
        // 6 kW felett: 2000 kredit alapdíj + 200 kredit/kW a 6 kW felett
        $toltottKilowatt = round($toltottKilowatt);
        if ($toltottKilowatt < 6.0) {
            $credits = $toltottKilowatt * 400;
        } else {
            $credits = 2000 + ($toltottKilowatt - 5) * 200;
        }

        $credits = max(0, floor($credits));

        # Felhasználói egyenleg frissítése
        $user->account_balance += $credits;
        $user->save();

        return $credits;
    }
}
