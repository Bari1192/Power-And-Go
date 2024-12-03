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
        $berlesKezdete = $this->berlesKezdete();
        $berlesIdotartam = $this->berlesIdotartama(); // másodperc
        $berlesVege = (clone $berlesKezdete)->modify("+{$berlesIdotartam} seconds");
        $megtettTavolsag = $this->megtettTavolsag($berlesIdotartam);
        $autoKategoria = $auto->kategoria_besorolas_fk;

        $parkolasok = $this->generaltParkolasok($berlesKezdete, $berlesVege);
        $teljesParkolasIdo = array_sum(array_column($parkolasok, 'hossza_perc'));
        $vezetesIdo = max(0, ($berlesIdotartam / 60) - $teljesParkolasIdo);

        # Parkolás kezd és vég, ha létezik parkolás
        $parkKezdIdo = !empty($parkolasok) ? $parkolasok[0]['kezd'] : null;
        $parkVegIdo = !empty($parkolasok) ? end($parkolasok)['veg'] : null;

        # Végösszeg számítása
        $berlesOsszeg = $this->berlesVegosszegSzamolas($arazas, $berlesIdotartam, $megtettTavolsag, $parkolasok, $autoKategoria);

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

    private function berlesIdotartama(): int
    {
        // 85% eséllyel 1-60 perc közötti bérlés, egyébként 1-3 nap közötti
        return random_int(1, 100) <= 85 ? random_int(60, 3600) : random_int(86400, 259200);
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
        $berlesIdotartam = $berlesIdoMasodperc / 3600; // Bérlés időtartam órában

        $parkolasokSzama = 0;
        if ($berlesIdotartam > 1 && $berlesIdotartam <= 3) {
            $parkolasokSzama = 1;
        } elseif ($berlesIdotartam >= 6 && $berlesIdotartam <= 16) {
            $parkolasokSzama = 2;
        } elseif ($berlesIdotartam > 16) {
            $parkolasokSzama = 3;
        }

        // A parkolási időket a bérlés időtartamához igazítjuk, max. 30%
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

    private function berlesVegosszegSzamolas(
        Arazas $arazas,
        int $idoKulonbseg,
        int $tavolsag,
        array $parkolasok,
        int $autoKategoria
    ): float {
        $idoPerc = $idoKulonbseg / 60;
        $napok = ceil($idoKulonbseg / 86400);

        // Napi bérlés, ha >= 24 óra (86400 másodperc)
        $napiBerles = $idoKulonbseg >= 86400;

        $berlesInditasa = $arazas->berles_ind ?? 0;
        $vezPercDij = $arazas->vez_perc ?? 0;
        $parkolasPercDij = $arazas->parkolas_perc ?? 0;
        $napiKmLimit = $arazas->napi_km_limit ?? 0;
        $kmDij = $arazas->km_dij ?? 0;

        $teljesParkolasIdo = array_sum(array_column($parkolasok, 'hossza_perc'));
        $vezetesPerc = max(0, $idoPerc - $teljesParkolasIdo);

        // Ha nem napi bérlés, a km díjat nem számítjuk!
        // Ha napi bérlés, akkor ha túllépi a km limitet, kifizetteti a km díjat is.
        $kmDijOsszeg = 0;
        if ($napiBerles) {
            $kmTobbseg = max(0, $tavolsag - ($napok * $napiKmLimit));
            $kmDijOsszeg = $kmTobbseg * $kmDij;
        }

        // Parkolási díj
        $parkolasiOsszeg = $teljesParkolasIdo * $parkolasPercDij;

        // Vezetési díj
        $vezetesOsszeg = $vezetesPerc * $vezPercDij;

        // Alapösszeg: (Indítási díj + vezetés + parkolás)
        // Napi bérlés esetén ehhez adódhat km díj, ha van.
        $alapOsszeg = $berlesInditasa + $vezetesOsszeg + $parkolasiOsszeg;
        if ($napiBerles) {
            $alapOsszeg += $kmDijOsszeg;
        }

        // Napi díjas konstrukció vizsgálata, csak ha napi bérlés
        if ($napiBerles) {
            $osszegNapiDijjal = $arazas->napidij;
            $napiBerlesek = NapiBerles::where('arazas_id', $arazas->id)
                ->where('auto_tipus', $autoKategoria)
                ->where('napok', '<=', $napok)
                ->orderByDesc('napok')
                ->get();

            foreach ($napiBerlesek as $napiBerlet) {
                if ($napiBerlet->napok <= $napok) {
                    $osszegNapiDijjal += $napiBerlet->ar;
                }
            }

            // Napi díjas konstrukcióhoz is hozzáadjuk a km díjat, ha túllépés történt.
            $osszegNapiDijjal += $kmDijOsszeg;
            return min($alapOsszeg, $osszegNapiDijjal);
        }

        // Nem napi bérlés esetén nincs napi díj összehasonlítás
        // és nincs km díj, már nem is számítottuk hozzá.
        return $alapOsszeg;
    }
}
