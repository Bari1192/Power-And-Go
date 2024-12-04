<?php

namespace Database\Factories;

use App\Models\Arazas;
use App\Models\Auto;
use App\Models\Felhasznalo;
use App\Models\LezartBerles;
use App\Models\NapiBerles;
use Illuminate\Database\Eloquent\Factories\Factory;

class LezartBerlesFactory extends Factory
{
    protected $model = LezartBerles::class;

    public function definition(): array
    {
        $auto = Auto::inRandomOrder()->first();
        $felhasznalo = Felhasznalo::inRandomOrder()->first();

        $arazas = Arazas::where('auto_besorolas', $auto->kategoria_besorolas_fk)
            ->where('elofiz_azon', $felhasznalo->elofiz_id)
            ->first();

        if (!$arazas) {
            throw new \Exception("Arazas nem található.");
        }

        # Bérlési időszak generálása
        $autoKategoria = $auto->kategoria_besorolas_fk;
        $berlesKezdete = $this->berlesKezdete();
        $berlesIdotartam = $this->berlesIdotartama($autoKategoria); ## másodperc - rand_int-ből!!!
        $berlesVege = (clone $berlesKezdete)->modify("+{$berlesIdotartam} seconds");
        $megtettTavolsag = $this->megtettTavolsag($berlesIdotartam);

        $parkolasok = $this->generaltParkolasok($berlesKezdete, $berlesVege);
        $teljesParkolasIdo = array_sum(array_column($parkolasok, 'hossza_perc'));
        $vezetesIdo = max(0, ($berlesIdotartam / 60) - $teljesParkolasIdo);

        # Parkolás kezd és vég - ha egyáltalán volt és létezik a parkolás
        $parkKezdIdo = !empty($parkolasok) ? $parkolasok[0]['kezd'] : null;
        $parkVegIdo = !empty($parkolasok) ? end($parkolasok)['veg'] : null;

        # Végösszeg számítása
        $berlesOsszeg = $this->berlesVegosszegSzamolas($arazas, $berlesIdotartam, $megtettTavolsag, $parkolasok, $autoKategoria, $berlesIdotartam);

        return [
            'auto_azonosito' => $auto->autok_id,
            'auto_kategoria' => $auto->kategoria_besorolas_fk,
            'szemely_id_fk' => $felhasznalo->szemely_id,
            'berles_kezd_datum' => $berlesKezdete->format('Y-m-d'),
            'berles_kezd_ido' => $berlesKezdete->format('H:i:s'),
            'berles_veg_datum' => $berlesVege->format('Y-m-d'),
            'berles_veg_ido' => $berlesVege->format('H:i:s'),
            'megtett_tavolsag' => $megtettTavolsag,
            'parkolas_kezd' => $parkKezdIdo,
            'parkolas_veg' => $parkVegIdo,
            'parkolasi_perc' => $teljesParkolasIdo,
            'vezetesi_perc' => $vezetesIdo,
            'berles_osszeg' => $berlesOsszeg,
        ];
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

    private function megtettTavolsag(int $idoKulonbseg): int
    {
        if ($idoKulonbseg <= 1800) {
            return random_int(5, 10);
        } elseif ($idoKulonbseg <= 3600) {
            return random_int(10, 20);
        } elseif ($idoKulonbseg <= 7200) {
            return random_int(10, 35);
        } elseif ($idoKulonbseg <= 14400) {
            return random_int(25, 45);
        } elseif ($idoKulonbseg <= 28800) {
            return random_int(35, 60);
        } elseif ($idoKulonbseg <= 86400) {
            return random_int(40, 80);
        } else {
            return random_int(100, 150);
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

        ## A parkolási időket a bérlés időtartamához igazítjuk, max. 30%
        $maxParkolasPerc = (int)(($berlesIdoMasodperc / 60) * 0.3);
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

    private function berlesVegosszegSzamolas(Arazas $arazas, int $idoKulonbseg, int $tavolsag, array $parkolasok, int $autoKategoria, $berlesIdotartam): float
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
        $parkolasiOsszeg = $teljesParkolasIdo * $parkolasPercDij;

        ## Vezetési díj
        $vezetesOsszeg = $vezetesPerc * $vezPercDij;

        ## Alap konstrukció: vezetési díj + parkolási díj + indítási díj + km díj (ha van)
        $alapOsszeg = $berlesInditasa + $vezetesOsszeg + $parkolasiOsszeg + $kmDijOsszeg;

        ## Ha 24 órán belüli bérlésről van szó a 2-es vagy 4-es kategóriában, legalább a napidíjat kell visszaadni
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
            $napiBerlesek = NapiBerles::where('arazas_id', $arazas->id)
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
