<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\CarUserrentCharge;
use App\Models\User;
use App\Policies\CarRefreshService;
use DateTime;
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
    private function calculateTimes(DateTime $start, DateTime $end): array
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
    /**
     *###########################################################################################
     *########################## LOGIKAI SZABÁLYZAT #############################################
     *###########################################################################################

     *                  >> [22 kW-os töltő esetén 0-ról 100%-ra] <<
     *########### Adott autókra lebontva átlagosan megmutatja a töltési időket ###########
     * [1] [18 kWh]-> 45-60p => 100%            || 0.3-0.4 kwH / perc
     * [2] [33 kWh | Kangoo]-> 90-120p 100%     || 0.3-0.4 kwH / perc
     * [3] [36 kWh]-> 90-120p 100%              || 0.3-0.4 kwH / perc
     * [4] [75 kWh | Vivaro]-> 90-120p 100%     || 0.37-0.4 kwH / perc
     * [5] [65 kWh | Niro]-> 120-130p 100%      || 0.5-0.6 kwH / perc
     
     
     *                  >> [50 kW-os töltő esetén 0-ról 100%-ra] <<
     *########### Adott autókra lebontva átlagosan megmutatja a töltési időket ###########
     * [1] [18 kWh]| >> 43p 100%                || 0.40-0.43 kw / perc
     * [2] [33 kWh | >> 40p 100%                || 0.8-0.83 kw / perc
     * [3] [36 kWh]| >> 43p 100%                || 0.8-0.83 kw / perc
     * [4] [75 kWh | >> 90p 100%                || 0.8-0.83 kw / perc
     * [5] [65 kWh | >> 78-80p 100%             || 0.8-0.83 kw / perc
     *###########################################################################################
     */
    public function kellHozzaTolteniAutot(int $berlesIdotartam, int $megtettTavolsag, Car $car): bool
    {
        if ($car->power_percent > 60 || $berlesIdotartam < 20) {
            return false;
        }
        $egyKwKilometerben = round(($car->fleet->driving_range / $car->fleet->motor_power), 2);
        $jelenlegiToltesKw = $car->power_kw;
        $jelenlegiToltesSzazalek = round(($jelenlegiToltesKw / $car->fleet->motor_power) * 100, 2);

        ## Max megt. táv, perc, fogyasztás a jelenlegi töltöttséggel.
        $maxtavJelenlegiToltessel = $egyKwKilometerben * $jelenlegiToltesKw;
        $maxTavoPercekreValtva = $maxtavJelenlegiToltessel * 2;
        $fogyasztas = $megtettTavolsag / $egyKwKilometerben;

        if (($megtettTavolsag > $maxtavJelenlegiToltessel || $berlesIdotartam > $maxTavoPercekreValtva &&
                $fogyasztas > $jelenlegiToltesKw) ||
            ($berlesIdotartam > 60 && $jelenlegiToltesSzazalek <= 35.0) ||
            ($berlesIdotartam > 180 && $jelenlegiToltesSzazalek <= 50.0)
        ) {
            return true;
        }
        return false;
    }

    public function generaljToltest(Car $car, User $user, DateTime $berlesKezdete, DateTime $berlesVege, int $berlesIdoTartam): array
    {
        # 1. Töltési sebesség 
        $toltesSebesseg = match ($car->category_id) {
            1, 2, 3 => fake()->randomFloat(2, 0.32, 0.37),
            4 => fake()->randomFloat(2, 0.37, 0.40),
            5 => fake()->randomFloat(2, 0.51, 0.61),
            default => fake()->randomFloat(2, 0.35, 0.40),
        };
        $start_percent = $car->power_percent;
        $start_kw = $car->power_kw;
        $chargingStart = (clone $berlesKezdete)->modify('+5 minutes');

        $times = $this->calculateTimes($berlesKezdete, $berlesVege);
        $berlesIdoTartam = $times['minutes'];

        # 2. Min. töltési idő
        $minimumToltesIdo = 5;

        # 3. Max. töltési idő
        $maxToltesIdoSzazSzazalekra = floor(($car->fleet->motor_power - $car->power_kw) / $toltesSebesseg);
        # 4. Korlátozások 
        $maxLehetségesToltesiIdo = min($berlesIdoTartam - 10, $maxToltesIdoSzazSzazalekra);
        # 5. Fix töltési idő generálása
        $toltesiIdo = fake()->numberBetween($minimumToltesIdo, $maxLehetségesToltesiIdo);
        $validaltToltesVege = (clone $chargingStart)->modify('+' . $toltesiIdo . ' minutes');

        if ($validaltToltesVege > $berlesVege) {
            return [];
        }
        # 5. Töltés kalkuláció & korrekció 
        $toltottKilowatt = max(0, $toltesiIdo * $toltesSebesseg);
        $ujToltesKw = min($car->fleet->motor_power, $car->power_kw + round($toltottKilowatt, 1));
        $ujToltesSzazalek = round(($ujToltesKw / $car->fleet->motor_power) * 100, 2);

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
            'charged_kw' => floor($toltottKilowatt), # nincs "túlszámlázás"
            'credits' => $credits,
        ];
    }
    public function chargingCreditsReturn(User $user, int $toltottKilowatt): int
    {
        if ($toltottKilowatt < 6.0) {
            $credits = $toltottKilowatt * 400;
        } else {
            $credits = 2000 + ($toltottKilowatt-5) * 200;
        }
        $credits = max(0, $credits);

        # Felh. egyenleg frissítése
        $user->account_balance += $credits;
        $user->save();
        return $credits;
    }
}
