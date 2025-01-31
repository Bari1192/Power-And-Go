<?php

namespace Database\Factories;

use App\Models\CarUserRentParking;
use App\Models\Price;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarUserRentParkingFactory extends Factory
{
    protected $model = CarUserRentParking::class;

    public function definition(): array
    {
        return [];
    }
    public function generaltParkolasok(\DateTime $berlesKezdete, \DateTime $berlesVege, int $categoryId, int $subId): array
    {
        $parkolasok = [];

        $berlesIdoMasodperc = $berlesVege->getTimestamp() - $berlesKezdete->getTimestamp();
        $berlesIdotartam = $berlesIdoMasodperc / 3600;

        $parkolasokSzama = 0;
        if ($berlesIdotartam > 1 && $berlesIdotartam <= 3) {
            $parkolasokSzama = random_int(1, 2);
        } elseif ($berlesIdotartam >= 6 && $berlesIdotartam <= 16) {
            $parkolasokSzama = random_int(2, 5);
        } elseif ($berlesIdotartam > 16) {
            $parkolasokSzama = random_int(3, 6);
        }

        $parkolasArany = rand(10, 50) / 100;
        $maxParkolasPerc = (int)(($berlesIdoMasodperc / 60) * $parkolasArany);
        $minParkolas = 5;
        $maxParkolas = max($minParkolas, $maxParkolasPerc);

        $price = Price::where('category_class', $categoryId)
            ->where('sub_id', $subId)
            ->first();
            $parkolasPercDij = $price->parking_minutes;

        for ($i = 0; $i < $parkolasokSzama; $i++) {
            $randomKezdIdo = $this->veletlenszeruIdo($berlesKezdete, $berlesVege);

            $randomHossz = $minParkolas;
            if ($maxParkolas > $minParkolas) {
                $randomHossz = random_int($minParkolas, $maxParkolas);
            }

            $randomVegeIdo = (clone $randomKezdIdo)->modify("+{$randomHossz} minutes");
            if ($randomVegeIdo > $berlesVege) {
                $randomVegeIdo = $berlesVege;
            }

            $parkolasHosszaPerc = max(0, ($randomVegeIdo->getTimestamp() - $randomKezdIdo->getTimestamp()) / 60);

            $price = Price::where('category_class', $categoryId)
                ->where('sub_id', $subId)
                ->first();

            $parkolasDijAdatok = $this->parkolasiKoltsegek([[
                'kezd' => $randomKezdIdo->format('Y-m-d H:i:s'),
                'veg' => $randomVegeIdo->format('Y-m-d H:i:s'),
                'hossza_perc' => $parkolasHosszaPerc,
            ]], $parkolasPercDij, $subId);

            $totalCost = $parkolasDijAdatok['ejszakaiParkolasiOsszeg'] + $parkolasDijAdatok['normalParkolasiOsszeg'];

            $parkolasok[] = [
                'kezd' => $randomKezdIdo->format('Y-m-d H:i:s'),
                'veg' => $randomVegeIdo->format('Y-m-d H:i:s'),
                'hossza_perc' => $parkolasHosszaPerc,
                'total_cost' => intval($totalCost),
            ];
        }
        return $parkolasok;
    }

    private function veletlenszeruIdo(\DateTime $start, \DateTime $end): \DateTime
    {
        $idoTartam = $end->getTimestamp() - $start->getTimestamp();
        $veletlenOffset = random_int(0, $idoTartam);
        return (clone $start)->modify("+{$veletlenOffset} seconds");
    }

    public function parkolasiKoltsegek(array $parkolasok, $parkolasPercDij, int $userSubId,): array
    {
        $ejszakaiParkolasIdeje = 0;
        $normalParkolasIdeje = 0;

        foreach ($parkolasok as $parking) {
            $kezdIdo = new DateTime($parking['kezd']);
            $vegIdo = new DateTime($parking['veg']);

            # Éjszakai intervallum: 22:00 – 07:00
            $ejszakaiParkKezd = new DateTime($kezdIdo->format('Y-m-d') . ' 22:00:00');
            $ejszakaiParkVeg  = new DateTime($vegIdo->format('Y-m-d') . ' 07:00:00');

            if ($kezdIdo >= $ejszakaiParkKezd || $vegIdo <= $ejszakaiParkVeg) {
                if ($kezdIdo >= $ejszakaiParkKezd && $vegIdo <= $ejszakaiParkVeg) {
                    $ejszakaiParkolasIdeje += ($vegIdo->getTimestamp() - $kezdIdo->getTimestamp()) / 60;
                } elseif ($kezdIdo >= $ejszakaiParkKezd) {
                    $ejszakaiParkolasIdeje += ($vegIdo->getTimestamp() - $ejszakaiParkKezd->getTimestamp()) / 60;
                } elseif ($vegIdo <= $ejszakaiParkVeg) {
                    $ejszakaiParkolasIdeje += ($ejszakaiParkVeg->getTimestamp() - $kezdIdo->getTimestamp()) / 60;
                }
            } else {
                $normalParkolasIdeje += ($vegIdo->getTimestamp() - $kezdIdo->getTimestamp()) / 60;
            }
        }
        $ejszakaiParkolasiOsszeg = $ejszakaiParkolasIdeje * $parkolasPercDij;
        $normalParkolasiOsszeg  = $normalParkolasIdeje * $parkolasPercDij;

        return [
            'ejszakaiParkolasIdeje'    => $ejszakaiParkolasIdeje,
            'normalParkolasIdeje'     => $normalParkolasIdeje,
            'ejszakaiParkolasiOsszeg' => $ejszakaiParkolasiOsszeg,
            'normalParkolasiOsszeg'   => $normalParkolasiOsszeg,
        ];
    }
}
