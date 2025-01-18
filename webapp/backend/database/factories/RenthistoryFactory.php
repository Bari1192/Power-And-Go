<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\Dailyrental;
use App\Models\Price;
use App\Models\Renthistory;
use App\Models\User;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class RenthistoryFactory extends Factory
{
    protected $model = Renthistory::class;

    public function definition(): array
    {
        $auto = Car::with('fleet')->where('status', 1)->first(); // carstatus alapján SZABAD.
        do {
            $autoKategoria = $auto->category_id;
            $flottacarmodel = $auto->fleet;

            $nyitasToltesSzazalek = $auto->power_percent;
            $nyitasToltesKw = round($auto->fleet->motor_power * ($nyitasToltesSzazalek / 100), 1);

            $berlesIdotartam = $this->berlesIdotartama($autoKategoria); ## másodperc - rand_int-ből!!!
            $megtettTavolsag = $this->megtettTavolsag($berlesIdotartam, $auto);

            $egyKwKilometerben = $flottacarmodel->driving_range / $flottacarmodel->motor_power;
            $kwFogyasztas = round($megtettTavolsag / $egyKwKilometerben, 1);
            $zaraskoriToltesKw = max($nyitasToltesKw - $kwFogyasztas, 0);
            $zarasToltesSzazalek = round(($zaraskoriToltesKw / $flottacarmodel->motor_power) * 100, 2);

            ## 1% alatt nem indulhat el a bérlés, ha valaki úgy zárta le az autót!
            if ($zarasToltesSzazalek >= 1.0) {
                ## 15% alatt zárják le, akkor egyből vegye ki a rendszer az autót és -> instant bünti érte!
                if ($zarasToltesSzazalek <= 15.0) {
                    $auto->status = 7;
                    $auto->save();
                    ## Autó "kritikus töltés" értékre kerül -> nem foglalható!
                }
                break;
            } else {
                continue;
            }
        } while (true);


        $user = User::inRandomOrder()->firstOrFail(); 
        $arazas = Price::where('category_class', $auto->category_id)
            ->where('sub_id', $user->sub_id)
            ->first();
        $berlesKezdete = $this->berlesKezdete();
        $berlesVege = (clone $berlesKezdete)->modify("+{$berlesIdotartam} seconds");

        $parkolasok = $this->generaltParkolasok($berlesKezdete, $berlesVege);
        $teljesParkolasIdo = array_sum(array_column($parkolasok, 'hossza_perc'));
        $vezetesIdo = max(0, ($berlesIdotartam / 60) - $teljesParkolasIdo);

        # Parkolás kezd és vég - ha egyáltalán volt és létezik a parkolás
        $parkKezdIdo = !empty($parkolasok) ? $parkolasok[0]['kezd'] : null;
        $parkVegIdo = !empty($parkolasok) ? end($parkolasok)['veg'] : null;

        # Végösszeg számítása
        $berlesOsszeg = $this->berlesVegosszegSzamolas($arazas, $user, $berlesIdotartam, $megtettTavolsag, $parkolasok, $autoKategoria, $berlesIdotartam);

        # Adott autonak a flottacarmodelának a hatótávja OSZTVA a teljesitményével ==> 1kw = 7.3768 km (pl.)
        $this->autoToltesFrissites($auto, $zarasToltesSzazalek, $zaraskoriToltesKw);
        $this->autoKmOraFrissites($auto, $megtettTavolsag);

        return [
            'car_id' => $auto->id,
            'category_id' => $auto->category_id,
            'user_id' => $user->id,
            'start_percent' => $nyitasToltesSzazalek,
            'start_kw' => $nyitasToltesKw,
            'end_percent' => $zarasToltesSzazalek,
            'end_kw' => $zaraskoriToltesKw,
            'rent_start_date' => $berlesKezdete,
            'rent_start_time' => $berlesKezdete,
            'rent_end_date' => $berlesVege,
            'rent_end_time' => $berlesVege,
            'driving_distance' => $megtettTavolsag,
            'parking_start' => $parkKezdIdo,
            'parking_end' => $parkVegIdo,
            'parking_minutes' => $teljesParkolasIdo,
            'driving_minutes' => $vezetesIdo,
            'rental_cost' => $berlesOsszeg,
            'invoice_date'=>now(),
            'rentstatus' => 2,// Lezárt bérlés -> BillSeeder generáláshoz
        ];
    }
    private function autoKmOraFrissites(Car $auto, $megtettTavolsag): void
    {
        $auto->odometer += $megtettTavolsag;
        $auto->save();
    }

    private function autoToltesFrissites(Car $auto, float $zarasToltesSzazalek, float $zaraskoriToltesKw): void
    {
        $auto->power_percent = $zarasToltesSzazalek;
        $auto->power_kw = max($zaraskoriToltesKw, 0);
        $auto->estimated_range = round(($auto->fleet->driving_range / 100) * $auto->power_percent, 1);
        $auto->save();
    }

    private function berlesKezdete(): \DateTime
    {
        return fake()->dateTimeBetween('-180 days', 'now');
    }

    private function berlesIdotartama($autoKategoria): int
    {
        if (in_array($autoKategoria, [2, 5])) {
            return random_int(1, 100) <= 66 ? random_int(1652, 4267) :  random_int(86400, 259200);
            ## 34-66 arányban rövid & hosszútávú bérlés lesz -> inkább rövid a "pakolós furgon"
        } elseif ($autoKategoria == 4) {
            return random_int(1, 100) <= 30 ? random_int(86000, 87000) :  random_int(86400, 259200);
            ## 30-70 arányban rövid & hosszútávú bérlés lesz -> inkább hosszú a "kényelmes Niro"
            ## 30 %-ban cirka 24 óra és akörüli bérlés || 70%-ban pedig 1-3 napos bérlés.
        } else {
            return random_int(1, 100) <= 80 ? random_int(60, 3600) : random_int(86400, 259200);
            ## 80% eséllyel 1-60 perc közötti bérlés, egyébként 1-3 nap közötti
        }
    }

    private function megtettTavolsag(int $idoKulonbseg, Car $auto): int
    {
        $aktualisdriving_range = round(($auto->fleet->driving_range / 100) * $auto->power_percent);
        if ($idoKulonbseg <= 1800) {
            return min(random_int(5, 10), $aktualisdriving_range);
            ## A random gen. távot MINDIG összehasonlítjuk az aktuális hatótávval,
            ## A kisebb értéket választjuk, hogy ne "lépje túl" az akksi kapacitást.
        } elseif ($idoKulonbseg <= 3600) {
            return min(random_int(10, 20), $aktualisdriving_range);
        } elseif ($idoKulonbseg <= 7200) {
            return min(random_int(10, 35), $aktualisdriving_range);
        } elseif ($idoKulonbseg <= 14400) {
            return min(random_int(25, 45), $aktualisdriving_range);
        } elseif ($idoKulonbseg <= 28800) {
            return min(random_int(35, 60), $aktualisdriving_range);
        } elseif ($idoKulonbseg <= 86400) {
            return min(random_int(40, 80), $aktualisdriving_range);
        } else {
            return min(random_int(100, 150), $aktualisdriving_range);
        }
    }

    private function veletlenszeruIdo(\DateTime $start, \DateTime $end): \DateTime
    {
        $idoTartam = $end->getTimestamp() - $start->getTimestamp();
        $veletlenOffset = random_int(0, $idoTartam);
        return (clone $start)->modify("+{$veletlenOffset} seconds");
    }

    private function generaltParkolasok(\DateTime $berlesKezdete, \DateTime $berlesVege): array
    {
        $parkolasok = [];
        $berlesIdoMasodperc = $berlesVege->getTimestamp() - $berlesKezdete->getTimestamp();
        $berlesIdotartam = $berlesIdoMasodperc / 3600; ## Bérlés időtartam ÓRÁBAN!

        $parkolasokSzama = 0;
        if ($berlesIdotartam > 1 && $berlesIdotartam <= 3) {
            $parkolasokSzama = 1;
        } elseif ($berlesIdotartam >= 6 && $berlesIdotartam <= 16) {
            $parkolasokSzama = 2;
        } elseif ($berlesIdotartam > 16) {
            $parkolasokSzama = 3;
        }

        ## A parkolási időket a bérlés időtartamához igazítjuk, max. 50%
        $maxParkolasPerc = (int)(($berlesIdoMasodperc / 60) * 0.5);
        $minParkolas = 5;
        $maxParkolas = max($minParkolas, $maxParkolasPerc);

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

            $parkolasok[] = [
                'kezd' => $randomKezdIdo->format('Y-m-d H:i:s'),
                'veg' => $randomVegeIdo->format('Y-m-d H:i:s'),
                'hossza_perc' => $parkolasHosszaPerc,
            ];
        }

        return $parkolasok;
    }

    private function berlesVegosszegSzamolas(Price $arazas, $user, int $idoKulonbseg, int $tavolsag, array $parkolasok, int $autoKategoria, $berlesIdotartam): float
    {
        $idoPerc = $idoKulonbseg / 60; ## Másodpercek átváltása percre
        $days = ceil($idoKulonbseg / 86400); ## days kiszámítása

        $berlesInditasa = $arazas->rental_start;
        $vezPercDij = $arazas->driving_minutes;
        $parkolasPercDij = $arazas->parking_minutes;
        $napiKmLimit = $arazas->daily_km_limit;
        $kmDij = $arazas->km_fee;

        $teljesParkolasIdo = array_sum(array_column($parkolasok, 'hossza_perc'));
        $vezetesPerc = max(0, $idoPerc - $teljesParkolasIdo);

        ## Km díj kiszámítása
        $kmTobbseg = max(0, $tavolsag - ($days * $napiKmLimit));
        $kmDijOsszeg = $kmTobbseg * $kmDij;

        ## Parkolási díj
        $ejszakaiParkolasIdeje = 0;
        $normalParkolasIdeje = 0;
        foreach ($parkolasok as $parking) {
            $kezdIdo = new DateTime($parking['kezd']);
            $vegIdo = new DateTime($parking['veg']);

            $ejszakaiParkKezd = new DateTime($kezdIdo->format('Y-m-d') . ' 22:00:00');
            $ejszakaiParkVeg = new DateTime($vegIdo->format('Y-m-d') . ' 07:00:00');

            if ($kezdIdo >= $ejszakaiParkKezd || $vegIdo <= $ejszakaiParkVeg) {
                // Éjszakai parkolás idejének kiszámítása itt
                if ($kezdIdo >= $ejszakaiParkKezd && $vegIdo <= $ejszakaiParkVeg) {
                    $ejszakaiParkolasIdeje += ($vegIdo->getTimestamp() - $kezdIdo->getTimestamp()) / 60;
                } elseif ($kezdIdo >= $ejszakaiParkKezd) {
                    $ejszakaiParkolasIdeje += ($vegIdo->getTimestamp() - $ejszakaiParkKezd->getTimestamp()) / 60;
                } elseif ($vegIdo <= $ejszakaiParkVeg) {
                    $ejszakaiParkolasIdeje += ($ejszakaiParkVeg->getTimestamp() - $kezdIdo->getTimestamp()) / 60;
                }
            } else {
                // AMúgy meg a normál parkolási idő kiszámítása
                $normalParkolasIdeje += ($vegIdo->getTimestamp() - $kezdIdo->getTimestamp()) / 60;
            }
        }
        $ejszakaiParkolasiOsszeg = $ejszakaiParkolasIdeje * $parkolasPercDij;
        $normalParkolasiOsszeg = $normalParkolasIdeje * $parkolasPercDij;

        ## Vezetési díj
        $vezetesOsszeg = $vezetesPerc * $vezPercDij;

        ## Alap konstrukció: vezetési díj + parkolási díj + indítási díj + km díj (ha van)
        $alapOsszeg = $berlesInditasa + $vezetesOsszeg + $normalParkolasiOsszeg + $kmDijOsszeg;

        # Csak a 4-es VIP előfizetésre vonatkozzon ez!
        if ($days <= 1 && $user->sub_id == 4) {
            $alapOsszeg = $alapOsszeg - $ejszakaiParkolasiOsszeg;
        } else {
            $alapOsszeg = $alapOsszeg;
        }


        ## Ha 24 órán belüli bérlésről van szó a 2-es vagy 4-es kategóriában, legalább a napidíjat kell visszaadni
        if ($days <= 1 && in_array($autoKategoria, [1, 3])) {
            $minimumOsszeg = $alapOsszeg;
            $maximumOsszeg = $arazas->daily_fee + $kmDijOsszeg + $berlesInditasa;
            return min($minimumOsszeg, $maximumOsszeg);
        }
        if ($days <= 1 && in_array($autoKategoria, [2, 4])) {
            $minimumOsszeg = $arazas->daily_fee + $kmDijOsszeg + $berlesInditasa;
            return max($minimumOsszeg, $alapOsszeg);
        }
        if (($berlesIdotartam / 60) <= 180 && $arazas->category_class == 5) {
            $minimumOsszeg = $arazas->three_hour_fee + $kmDijOsszeg + $berlesInditasa;
            return max($minimumOsszeg, $alapOsszeg);
        } elseif (($berlesIdotartam / 60) > 180 && $berlesIdotartam < 360 && $arazas->category_class == 5) {
            $minimumOsszeg = $arazas->three_hour_fee + $kmDijOsszeg + $berlesInditasa;
            $maximumOsszeg = $arazas->six_hour_fee + $kmDijOsszeg + $berlesInditasa;
            return min($minimumOsszeg, $maximumOsszeg);
        } elseif (($berlesIdotartam / 60) >= 360 && $berlesIdotartam < 720 && $arazas->category_class == 5) {
            $minimumOsszeg = $arazas->six_hour_fee + $kmDijOsszeg + $berlesInditasa;
            $maximumOsszeg = $arazas->twelve_hour_fee + $kmDijOsszeg + $berlesInditasa;
            return min($minimumOsszeg, $maximumOsszeg);
        } elseif (($berlesIdotartam / 60) >= 720 && $berlesIdotartam < 1440 && $arazas->category_class == 5) {
            $minimumOsszeg = $arazas->twelve_hour_fee + $kmDijOsszeg + $berlesInditasa;
            $maximumOsszeg = $arazas->daily_fee + $kmDijOsszeg + $berlesInditasa;
            return min($minimumOsszeg, $maximumOsszeg);
        }


        ## Ha többnapos a bérlés ==> lekérjük a NapiBerles táblából annak az értékekét.
        if ($days > 1) {
            $napiBerlesek = Dailyrental::where('prices_id', $arazas->id)
                ->where('category_class', $autoKategoria)
                ->orderBy('days')
                ->get();

            if ($napiBerlesek->isEmpty()) {
                throw new \Exception("NapiBerles adatok nem találhatók!");
            }

            $napiDijTomb = $napiBerlesek->pluck('price')->toArray();
            $tobbNaposDij = $napiDijTomb[$days - 2] ?? end($napiDijTomb);
            ## Mivel a napidíjak a 2. naptól indulnak, ezt figyelembevéve kell a $days-2.

            ## Többnapos bérlés => alapösszeggel +napi díjas összeggel hasonlítunk
            return min($alapOsszeg, $tobbNaposDij + $kmDijOsszeg + $berlesInditasa);
        }
        return $alapOsszeg;
    }
}
