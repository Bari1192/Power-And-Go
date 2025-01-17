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
            $autoKategoria = $auto->kategoria;
            $flottaTipus = $auto->fleet;

            $nyitasToltesSzazalek = $auto->toltes_szaz;
            $nyitasToltesKw = round($auto->fleet->teljesitmeny * ($nyitasToltesSzazalek / 100), 1);

            $berlesIdotartam = $this->berlesIdotartama($autoKategoria); ## másodperc - rand_int-ből!!!
            $megtettTavolsag = $this->megtettTavolsag($berlesIdotartam, $auto);

            $egyKwKilometerben = $flottaTipus->hatotav / $flottaTipus->teljesitmeny;
            $kwFogyasztas = round($megtettTavolsag / $egyKwKilometerben, 1);
            $zaraskoriToltesKw = max($nyitasToltesKw - $kwFogyasztas, 0);
            $zarasToltesSzazalek = round(($zaraskoriToltesKw / $flottaTipus->teljesitmeny) * 100, 2);

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


        $felhasznalo = User::inRandomOrder()->firstOrFail(); 
        $arazas = Price::where('auto_besorolas', $auto->kategoria)
            ->where('elofiz_azon', $felhasznalo->elofiz_id)
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
        $berlesOsszeg = $this->berlesVegosszegSzamolas($arazas, $felhasznalo, $berlesIdotartam, $megtettTavolsag, $parkolasok, $autoKategoria, $berlesIdotartam);

        # Adott autonak a flottaTipusának a hatótávja OSZTVA a teljesitményével ==> 1kw = 7.3768 km (pl.)
        $this->autoToltesFrissites($auto, $zarasToltesSzazalek, $zaraskoriToltesKw);
        $this->autoKmOraFrissites($auto, $megtettTavolsag);

        return [
            'car_id' => $auto->id,
            'kategoria' => $auto->kategoria,
            'user_id' => $felhasznalo->id,
            'nyitas_szaz' => $nyitasToltesSzazalek,
            'nyitas_kw' => $nyitasToltesKw,
            'zaras_szaz' => $zarasToltesSzazalek,
            'zaras_kw' => $zaraskoriToltesKw,
            'berles_kezd_datum' => $berlesKezdete,
            'berles_kezd_ido' => $berlesKezdete,
            'berles_veg_datum' => $berlesVege,
            'berles_veg_ido' => $berlesVege,
            'megtett_tavolsag' => $megtettTavolsag,
            'parkolas_kezd' => $parkKezdIdo,
            'parkolas_veg' => $parkVegIdo,
            'parkolasi_perc' => $teljesParkolasIdo,
            'vezetesi_perc' => $vezetesIdo,
            'berles_osszeg' => $berlesOsszeg,
            'szamla_kelt'=>now(),
            'rentstatus' => 2,// Lezárt bérlés -> BillSeeder generáláshoz
        ];
    }
    private function autoKmOraFrissites(Car $auto, $megtettTavolsag): void
    {
        $auto->kilometerora += $megtettTavolsag;
        $auto->save();
    }

    private function autoToltesFrissites(Car $auto, float $zarasToltesSzazalek, float $zaraskoriToltesKw): void
    {
        $auto->toltes_szaz = $zarasToltesSzazalek;
        $auto->toltes_kw = max($zaraskoriToltesKw, 0);
        $auto->becs_tav = round(($auto->fleet->hatotav / 100) * $auto->toltes_szaz, 1);
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
        $aktualisHatotav = round(($auto->fleet->hatotav / 100) * $auto->toltes_szaz);
        if ($idoKulonbseg <= 1800) {
            return min(random_int(5, 10), $aktualisHatotav);
            ## A random gen. távot MINDIG összehasonlítjuk az aktuális hatótávval,
            ## A kisebb értéket választjuk, hogy ne "lépje túl" az akksi kapacitást.
        } elseif ($idoKulonbseg <= 3600) {
            return min(random_int(10, 20), $aktualisHatotav);
        } elseif ($idoKulonbseg <= 7200) {
            return min(random_int(10, 35), $aktualisHatotav);
        } elseif ($idoKulonbseg <= 14400) {
            return min(random_int(25, 45), $aktualisHatotav);
        } elseif ($idoKulonbseg <= 28800) {
            return min(random_int(35, 60), $aktualisHatotav);
        } elseif ($idoKulonbseg <= 86400) {
            return min(random_int(40, 80), $aktualisHatotav);
        } else {
            return min(random_int(100, 150), $aktualisHatotav);
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

    private function berlesVegosszegSzamolas(Price $arazas, $felhasznalo, int $idoKulonbseg, int $tavolsag, array $parkolasok, int $autoKategoria, $berlesIdotartam): float
    {
        $idoPerc = $idoKulonbseg / 60; ## Másodpercek átváltása percre
        $napok = ceil($idoKulonbseg / 86400); ## Napok kiszámítása

        $berlesInditasa = $arazas->berles_ind;
        $vezPercDij = $arazas->vez_perc;
        $parkolasPercDij = $arazas->parkolas_perc;
        $napiKmLimit = $arazas->napi_km_limit;
        $kmDij = $arazas->km_dij;

        $teljesParkolasIdo = array_sum(array_column($parkolasok, 'hossza_perc'));
        $vezetesPerc = max(0, $idoPerc - $teljesParkolasIdo);

        ## Km díj kiszámítása
        $kmTobbseg = max(0, $tavolsag - ($napok * $napiKmLimit));
        $kmDijOsszeg = $kmTobbseg * $kmDij;

        ## Parkolási díj
        $ejszakaiParkolasIdeje = 0;
        $normalParkolasIdeje = 0;
        foreach ($parkolasok as $parkolas) {
            $kezdIdo = new DateTime($parkolas['kezd']);
            $vegIdo = new DateTime($parkolas['veg']);

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
        if ($napok <= 1 && $felhasznalo->elofiz_id == 4) {
            $alapOsszeg = $alapOsszeg - $ejszakaiParkolasiOsszeg;
        } else {
            $alapOsszeg = $alapOsszeg;
        }


        ## Ha 24 órán belüli bérlésről van szó a 2-es vagy 4-es kategóriában, legalább a napidíjat kell visszaadni
        if ($napok <= 1 && in_array($autoKategoria, [1, 3])) {
            $minimumOsszeg = $alapOsszeg;
            $maximumOsszeg = $arazas->napidij + $kmDijOsszeg + $berlesInditasa;
            return min($minimumOsszeg, $maximumOsszeg);
        }
        if ($napok <= 1 && in_array($autoKategoria, [2, 4])) {
            $minimumOsszeg = $arazas->napidij + $kmDijOsszeg + $berlesInditasa;
            return max($minimumOsszeg, $alapOsszeg);
        }
        if (($berlesIdotartam / 60) <= 180 && $arazas->auto_besorolas == 5) {
            $minimumOsszeg = $arazas->harom_ora_dij + $kmDijOsszeg + $berlesInditasa;
            return max($minimumOsszeg, $alapOsszeg);
        } elseif (($berlesIdotartam / 60) > 180 && $berlesIdotartam < 360 && $arazas->auto_besorolas == 5) {
            $minimumOsszeg = $arazas->harom_ora_dij + $kmDijOsszeg + $berlesInditasa;
            $maximumOsszeg = $arazas->hat_ora_dij + $kmDijOsszeg + $berlesInditasa;
            return min($minimumOsszeg, $maximumOsszeg);
        } elseif (($berlesIdotartam / 60) >= 360 && $berlesIdotartam < 720 && $arazas->auto_besorolas == 5) {
            $minimumOsszeg = $arazas->hat_ora_dij + $kmDijOsszeg + $berlesInditasa;
            $maximumOsszeg = $arazas->tizenketto_ora_dij + $kmDijOsszeg + $berlesInditasa;
            return min($minimumOsszeg, $maximumOsszeg);
        } elseif (($berlesIdotartam / 60) >= 720 && $berlesIdotartam < 1440 && $arazas->auto_besorolas == 5) {
            $minimumOsszeg = $arazas->tizenketto_ora_dij + $kmDijOsszeg + $berlesInditasa;
            $maximumOsszeg = $arazas->napidij + $kmDijOsszeg + $berlesInditasa;
            return min($minimumOsszeg, $maximumOsszeg);
        }


        ## Ha többnapos a bérlés ==> lekérjük a NapiBerles táblából annak az értékekét.
        if ($napok > 1) {
            $napiBerlesek = Dailyrental::where('arazas_id', $arazas->id)
                ->where('auto_tipus', $autoKategoria)
                ->orderBy('napok')
                ->get();

            if ($napiBerlesek->isEmpty()) {
                throw new \Exception("NapiBerles adatok nem találhatók!");
            }

            $napiDijTomb = $napiBerlesek->pluck('ar')->toArray();
            $tobbNaposDij = $napiDijTomb[$napok - 2] ?? end($napiDijTomb);
            ## Mivel a napidíjak a 2. naptól indulnak, ezt figyelembevéve kell a $napok-2.

            ## Többnapos bérlés => alapösszeggel +napi díjas összeggel hasonlítunk
            return min($alapOsszeg, $tobbNaposDij + $kmDijOsszeg + $berlesInditasa);
        }
        return $alapOsszeg;
    }
}
