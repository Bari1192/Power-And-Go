<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\Dailyrental;
use App\Models\Price;
use App\Models\Renthistory;
use App\Models\User;
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
        $idoKulonbseg = $berlesVege->getTimestamp() - $berlesKezdete->getTimestamp();

        $parkolasok = CarUserRentParkingFactory::new()
            ->generaltParkolasok($berlesKezdete, $berlesVege, $autoKategoria, $user->sub_id);

        $teljesParkolasIdo = array_sum(array_column($parkolasok, 'hossza_perc'));
        $vezetesIdo = max(0, ($berlesIdotartam / 60) - $teljesParkolasIdo);

        # Végösszeg számítása
        $berlesOsszeg = $this->berlesVegosszegSzamolas($arazas, $user, $idoKulonbseg, $megtettTavolsag, $autoKategoria, $berlesIdotartam, $berlesKezdete, $berlesVege);

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
            'rent_start' => $berlesKezdete,
            'rent_close' => $berlesVege,
            'distance' => $megtettTavolsag,
            'parking_minutes' => $teljesParkolasIdo,
            'driving_minutes' => $vezetesIdo,
            'rental_cost' => $berlesOsszeg,
            'invoice_date' => now(),
            'rentstatus' => 2, // Lezárt bérlés -> BillSeeder generáláshoz
            'parkolasok' => $parkolasok,
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
        } elseif ($autoKategoria == 4) {
            return random_int(1, 100) <= 30 ? random_int(86000, 87000) :  random_int(86400, 259200);
        } else {
            return random_int(1, 100) <= 80 ? random_int(60, 3600) : random_int(86400, 259200);
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
    private function berlesVegosszegSzamolas(Price $arazas, $user, int $idoKulonbseg, int $tavolsag, int $autoKategoria, $berlesIdotartam, \DateTime $berlesKezdete, \DateTime $berlesVege)
    {
        ## Összes parkolása összeadva + vezetés + nyitás + egyéb díjakkal.

        $idoPerc = $idoKulonbseg / 60;
        $days = ceil($idoKulonbseg / 86400);

        $berlesInditasa = $arazas->rental_start;
        $vezPercDij = $arazas->driving_minutes;
        $napiKmLimit = $arazas->daily_km_limit;
        $kmDij = $arazas->km_fee;

        ## Parkolási díj
        $parkolasok = CarUserRentParkingFactory::new()
        ->generaltParkolasok($berlesKezdete, $berlesVege, $autoKategoria, $user->sub_id);

        $teljesParkolasIdo = array_sum(array_column($parkolasok, 'hossza_perc'));
        $parkolasDijOsszeg = array_sum(array_column($parkolasok, 'total_cost'));

        ## Vezetési díj és km díj kiszámítása
        $vezetesPerc = max(0, $idoPerc - $teljesParkolasIdo);
        $vezetesOsszeg = $vezetesPerc * $vezPercDij;
        $kmTobbseg = max(0, $tavolsag - ($days * $napiKmLimit));
        $kmDijOsszeg = $kmTobbseg * $kmDij;

        ## Alap konstrukció: vezetési díj + parkolási díj + indítási díj + km díj (ha van)
        $alapOsszeg = $berlesInditasa + $vezetesOsszeg + $parkolasDijOsszeg + $kmDijOsszeg;

        # Csak a 4-es VIP előfizetésre vonatkozzon ez!
        if ($days <= 1 && $user->sub_id == 4) {
            $alapOsszeg = $alapOsszeg - $parkolasDijOsszeg;
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

            ## Többnapos bérlés => alapösszeggel +napi díjas összeggel hasonlítunk
            return min($alapOsszeg, $tobbNaposDij + $kmDijOsszeg + $berlesInditasa);
        }
        return $alapOsszeg;
    }
}
