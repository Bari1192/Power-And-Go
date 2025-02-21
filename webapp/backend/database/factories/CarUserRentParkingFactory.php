<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\CarUserRentParking;
use App\Models\Price;
use App\Models\User;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarUserRentParkingFactory extends Factory
{
    protected $model = CarUserRentParking::class;

    public function definition(): array
    {
        return [];
    }
    public function generaltParkolasok(
        DateTime $berlesKezdete,
        DateTime $berlesVege,
        Price $arazas,
        User $user,
        Car $auto,
        array $parkolasokAranyok
    ): array {
        $parkolasok = [];
        foreach ($parkolasokAranyok as $percek) {
            if ($percek < 5) continue;
            $kezd = fake()->dateTimeBetween(
                $berlesKezdete,
                (clone $berlesVege)->modify('-' . ($percek + 5) . ' minutes')
            );
            $veg = (clone $kezd)->modify('+' . $percek . ' minutes');
            $parkolasok[] = [
                'kezd' => $kezd->format('Y-m-d H:i:s'),
                'veg' => $veg->format('Y-m-d H:i:s'),
                'parking_minutes' => $percek,
                'total_cost' => $this->parkolasiKoltsegek($user, $auto, $arazas, [['kezd' => $kezd, 'veg' => $veg, 'parking_minutes' => $percek]])
            ];
        }
        return $parkolasok;
    }
    /**
     * PARKOLÁS & ÉJSZAKAI PARKOLÁSÉRT FELELŐS FÜGGVÉNY!
     */
    public function parkolasiKoltsegek(User $user, Car $auto, Price $arazas, array $parkolasok): int
    {
        if (empty($parkolasok)) {
            return 0;
        }
        $arazas = Price::where('category_class', $auto->category_id)
            ->where('sub_id', $user->sub_id)
            ->first();

        $percDij = $arazas->parking_minutes ?? 90;
        $totalCost = 0;

        foreach ($parkolasok as $parking) {
            $kezdIdo = $parking['kezd'] instanceof DateTime ? $parking['kezd'] : new DateTime($parking['kezd']);
            $vegIdo = $parking['veg'] instanceof DateTime ? $parking['veg'] : new DateTime($parking['veg']);

            $split = $this->calculateDayNightMinutes($kezdIdo, $vegIdo);
            if (!($user->sub_id === 4 && in_array($auto->category_id, [1, 2, 3]))) {
                $totalCost += $split['night'] * $percDij;
            }
            $totalCost += $split['day'] * $percDij;
        }

        return max(0, (int) round($totalCost));
    }

    /**
     * Felbontja a (start–end) intervallumot napokra,
     * és kiszámolja, hány perc esik 07:00–22:00 (day) és 22:00–07:00 (night) közé.
     * Többnapos intervallumokat is kezel.
     */
    public function calculateDayNightMinutes(DateTime $start, DateTime $end): array
    {
        if ($end <= $start) {
            return ['day' => 0, 'night' => 0];
        }
        $dayMinutes = 0;
        $nightMinutes = 0;
        $current = clone $start;

        while ($current < $end) {
            ## Az adott nap nappali sávja
            $dayStart = (clone $current)->setTime(7, 0);
            $dayEnd   = (clone $current)->setTime(22, 0);

            ## Nap vége = holnap 00:00
            $midnight = (clone $current)->modify('tomorrow')->setTime(0, 0);
            ## Meddig tart ez a "rész-nap"?
            $todayEnd = min($midnight, $end);

            ## Kiszámoljuk, mennyi perc az átfedés a [current–todayEnd] és a [dayStart–dayEnd] között
            $dayOverlap = $this->overlapInMinutes($current, $todayEnd, $dayStart, $dayEnd);

            ## A [current–todayEnd] összes perc
            $todayTotal = max(0, (int) floor(($todayEnd->getTimestamp() - $current->getTimestamp()) / 60));

            ## Éjszaka = total - nappali overlap
            $nightOverlap = max(0, $todayTotal - $dayOverlap);

            $dayMinutes   += $dayOverlap;
            $nightMinutes += $nightOverlap;

            ## Következő napra ugrunk
            $current = $midnight;
        }

        return [
            'day'   => $dayMinutes,
            'night' => $nightMinutes,
        ];
    }
    /**
     * Két intervallum (A=[startA,endA], B=[startB,endB]) átfedésének hossza percben.
     */
    private function overlapInMinutes(
        DateTime $startA,
        DateTime $endA,
        DateTime $startB,
        DateTime $endB
    ): int {
        $startMax = max($startA->getTimestamp(), $startB->getTimestamp());
        $endMin   = min($endA->getTimestamp(), $endB->getTimestamp());

        if ($endMin <= $startMax) {
            return 0;
        }

        return (int) floor(($endMin - $startMax) / 60);
    }
    public function userFullTimeRentValidation(
        DateTime $berlesKezdete,
        Car $auto,
        DateTime $berlesVege,
        Price $arazas,
        ?int &$vezetesIdo,
        array $parkolasok,
        User $user
    ): array {
        $totalSeconds = $berlesVege->getTimestamp() - $berlesKezdete->getTimestamp();
        $totalMinutes = round($totalSeconds / 60);
        $vezetesIdo = round($vezetesIdo ?? 0);

        $osszesParkolasIdo = array_sum(array_column($parkolasok, 'parking_minutes'));
        $maxParkingMinutes = round($totalMinutes * 0.6);

        ## max parkolási idő túllépésére
        if ($osszesParkolasIdo > $maxParkingMinutes) {
            $excessParking = $osszesParkolasIdo - $maxParkingMinutes;
            $vezetesIdo += $excessParking;

            if (!empty($parkolasok)) {
                $lastIndex = count($parkolasok) - 1;
                $parkolasok[$lastIndex]['parking_minutes'] = max(
                    5,
                    $parkolasok[$lastIndex]['parking_minutes'] - $excessParking
                );

                ## Frissíteni a végső időpontot
                $kezdIdo = new DateTime($parkolasok[$lastIndex]['kezd']);
                $vegIdo = (clone $kezdIdo)->modify(
                    '+' . $parkolasok[$lastIndex]['parking_minutes'] . ' minutes'
                );
                $parkolasok[$lastIndex]['veg'] = $vegIdo->format('Y-m-d H:i:s');

                ## Újraszámolni a költséget
                $parkolasok[$lastIndex]['total_cost'] = $this->parkolasiKoltsegek(
                    $user,
                    $auto,
                    $arazas,
                    [$parkolasok[$lastIndex]]
                );
            }
        }

        ## Végső vezetési idő kalkulálás
        $osszesParkolasIdo = array_sum(array_column($parkolasok, 'parking_minutes'));
        $vezetesIdo = $totalMinutes - round($osszesParkolasIdo);

        return [
            'parking' => $parkolasok,
            'driving' => max(0, $vezetesIdo),
        ];
    }
}
