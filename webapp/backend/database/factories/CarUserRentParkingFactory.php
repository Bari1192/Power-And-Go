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
        ?array $parkolasokAranyok
    ): array {
        if (empty($parkolasokAranyok)) {
            return [];
        }
        $parkolasok = [];
        foreach ($parkolasokAranyok as $key => $percek) {
            if ($percek < 5) continue; ## Túl rövid parkolásokat kihagyjuk
            $kezdIdo = fake()->dateTimeBetween(
                $berlesKezdete, 
                (clone $berlesVege)->modify('-' . ($percek + 5) . ' minutes')
            );
            $vegIdo = (clone $kezdIdo)->modify('+' . $percek . ' minutes');
            if ($vegIdo > $berlesVege) {
                $vegIdo = clone $berlesVege;
                $percek = floor(($vegIdo->getTimestamp() - $kezdIdo->getTimestamp()) / 60);
                if ($percek < 5) continue; ## Ha túl rövid lenne, akkor kihagyjuk
            }
            
            $parkolasok[] = [
                'kezd' => $kezdIdo->format('Y-m-d H:i:s'),
                'veg' => $vegIdo->format('Y-m-d H:i:s'),
                'parking_minutes' => $percek,
                'total_cost' => $this->parkolasiKoltsegek(
                    $user, 
                    $auto, 
                    $arazas, 
                    [['kezd' => $kezdIdo, 'veg' => $vegIdo, 'parking_minutes' => $percek]]
                )
            ];
        }
        return $parkolasok;
    }
    
    public function parkolasiKoltsegek(User $user, Car $auto, Price $arazas, array $parkolasok): int
    {
        if (empty($parkolasok)) {
            return 0;
        }
        $arazas = Price::where('category_class', $auto->category_id)
            ->where('sub_id', $user->sub_id)
            ->first();
        if (!$arazas) {
            return 0;
        }
        $percDij = $arazas->parking_minutes ?? 90;
        $totalCost = 0;
        foreach ($parkolasok as $parking) {
            $kezdIdo = $parking['kezd'] instanceof DateTime ? $parking['kezd'] : new DateTime($parking['kezd']);
            $vegIdo = $parking['veg'] instanceof DateTime ? $parking['veg'] : new DateTime($parking['veg']);
            $split = $this->calculateDayNightMinutes($kezdIdo, $vegIdo);
            $nightCost = 0;
            if (!(in_array($user->sub_id, [2, 4]) || in_array($auto->category_id, [2, 4, 5]))) {
                $nightCost = $split['night'] * $percDij;
            }
            $dayCost = $split['day'] * $percDij;
            $totalCost += $dayCost + $nightCost;
        }
        return max(0, (int) round($totalCost));
    }
    
    public function calculateDayNightMinutes(DateTime $start, DateTime $end): array
    {
        if ($end <= $start) {
            return ['day' => 0, 'night' => 0];
        }
        
        $dayMinutes = 0;
        $nightMinutes = 0;
        $current = clone $start;

        while ($current < $end) {
            ## Nappali időszak: 7:00 - 22:00
            $dayStart = (clone $current)->setTime(7, 0);
            $dayEnd   = (clone $current)->setTime(22, 0);
            $midnight = (clone $current)->modify('tomorrow')->setTime(0, 0);
            $todayEnd = min($midnight, $end);
            $dayOverlap = $this->overlapInMinutes($current, $todayEnd, $dayStart, $dayEnd);
            $todayTotal = max(0, (int) floor(($todayEnd->getTimestamp() - $current->getTimestamp()) / 60));
            $nightOverlap = max(0, $todayTotal - $dayOverlap);
            $dayMinutes   += $dayOverlap;
            $nightMinutes += $nightOverlap;
            $current = $midnight;
        }
        return [
            'day'   => $dayMinutes,
            'night' => $nightMinutes,
        ];
    }
    
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
        ?array $parkolasok,
        User $user
    ): array {
        // Teljes bérlés időtartama percben
        $totalSeconds = $berlesVege->getTimestamp() - $berlesKezdete->getTimestamp();
        $totalMinutes = floor($totalSeconds / 60);
        
        // Vezetési idő validálása - biztosítjuk, hogy szám legyen
        $vezetesIdo = floor($vezetesIdo ?? 0);
        
        // Összes parkolási idő kiszámítása
        $osszesParkolasIdo = 0;
        if (is_array($parkolasok)) {
            foreach ($parkolasok as $parkolas) {
                $osszesParkolasIdo += $parkolas['parking_minutes'] ?? 0;
            }
        }
        
        // Maximum parkolási idő korlátozása a teljes idő 60%-ára
        $maxParkingMinutes = floor($totalMinutes * 0.6);
        
        // Ha a parkolási idő túllépi a maximumot, korrigáljuk
        if ($osszesParkolasIdo > $maxParkingMinutes) {
            $excessParking = $osszesParkolasIdo - $maxParkingMinutes;
            $vezetesIdo += $excessParking;
            $osszesParkolasIdo = $maxParkingMinutes;
            
            // Az utolsó parkolás idejének csökkentése
            if (!empty($parkolasok)) {
                $lastIndex = count($parkolasok) - 1;
                $parkolasok[$lastIndex]['parking_minutes'] = max(
                    5,
                    $parkolasok[$lastIndex]['parking_minutes'] - $excessParking
                );
                
                // Parkolás vég időpontjának frissítése
                $kezdIdo = new DateTime($parkolasok[$lastIndex]['kezd']);
                $vegIdo = (clone $kezdIdo)->modify(
                    '+' . $parkolasok[$lastIndex]['parking_minutes'] . ' minutes'
                );
                $parkolasok[$lastIndex]['veg'] = $vegIdo->format('Y-m-d H:i:s');
                
                // Költség újraszámítása
                $parkolasok[$lastIndex]['total_cost'] = $this->parkolasiKoltsegek(
                    $user,
                    $auto,
                    $arazas,
                    [$parkolasok[$lastIndex]]
                );
            }
        }
        
        // Ellenőrizzük, hogy a vezetési idő + parkolási idő = teljes bérlési idő
        $osszesIdo = $osszesParkolasIdo + $vezetesIdo;
        if ($osszesIdo != $totalMinutes) {
            // Korrigáljuk a vezetési időt, hogy kijöjjön a teljes idő
            $vezetesIdo = $totalMinutes - $osszesParkolasIdo;
        }
        
        return [
            'parking' => $osszesParkolasIdo,
            'driving' => max(0, $vezetesIdo),
        ];
    }
}