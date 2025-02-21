<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\Dailyrental;
use App\Models\Price;
use App\Models\Renthistory;
use App\Models\User;
use App\Policies\CarRefreshService;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class RenthistoryFactory extends Factory
{
    protected $model = Renthistory::class;
    private CarRefreshService $carRefreshService;
    public function __construct()
    {
        parent::__construct();
        $this->carRefreshService = new CarRefreshService();
        $this->states = collect();
    }
    public function definition(): array
    {
        $parkingRecords = [];
        $parkolasok = [];
        $chargeData = [];
        $vezetesIdo = 0;
        $parkolasokDarabszam = 0;
        $toltesekGeneralas = 0;
        $teljesParkolasIdo = 0;

        do {
            $car = Car::with('fleet')
                ->where('status', 1)
                ->inRandomOrder()
                ->first();
            if ($car === null) {
                break;
            }
            $user = User::inRandomOrder()->first();
            $arazas = Price::where('category_class', $car->category_id)
                ->where('sub_id', $user->sub_id)
                ->first();

            ## 2. Bérlés időtartam és időpontok generálása
            $berlesKezdete = $this->berlesKezdete();
            $berlesIdotartam = $this->berlesIdotartama($car->category_id);
            $berlesVege = (clone $berlesKezdete)->modify("+{$berlesIdotartam} minutes");
            $times = $this->calculateTimes($berlesKezdete, $berlesVege);

            $nyitasToltesSzazalek = $car->power_percent;
            $nyitasToltesKw = $car->fleet->motor_power * ($nyitasToltesSzazalek / 100);

            ## 3. Megtett távolság kalkuláció
            $tavolsagAdatok = $this->megtettTavolsag($car, $berlesKezdete, $berlesVege);
            $megtettTavolsag = $tavolsagAdatok['megtettTavolsag'];
            $vezetesIdo = $tavolsagAdatok['vezetesIdo'];
            $parkolasokDarabszam = $tavolsagAdatok['parkolasokDarabszam'];
            $this->carRefreshService->frissitesTavolsagUtan($car, $megtettTavolsag);

            ## 4. Töltések kezelése
            while (CarUserrentChargeFactory::new()->kellHozzaTolteniAutot($times['minutes'], $megtettTavolsag, $car)) {
                $chargeData = CarUserrentChargeFactory::new()->generaljToltest($car, $user, $berlesKezdete, $berlesVege, $times['minutes']);
                if ($chargeData) {
                    $toltesekGeneralas++;
                    $parkingRecords[] = [
                        'kezd' => $chargeData['charging_start_date']->format('Y-m-d H:i:s'),
                        'veg' => $chargeData['charging_end_date']->format('Y-m-d H:i:s'),
                        'parking_minutes' => $chargeData['charging_time'],
                        'total_cost' => in_array($car->category_id, [4, 5]) ?
                            $chargeData['charging_time'] * 90 :
                            90 * $chargeData['charging_time']
                    ];
                    $teljesParkolasIdo += $chargeData['charging_time'];
                    $berlesVege = (clone $berlesVege)->modify("+{$chargeData['charging_time']} minutes");
                    $times = $this->calculateTimes($berlesKezdete, $berlesVege);
                }
                if ($toltesekGeneralas >= 2) {
                    break;
                }
            }
            ## 5. Parkolások generálása
            if ($parkolasokDarabszam > 0) {
                $parkolasok = CarUserRentParkingFactory::new()->generaltParkolasok(
                    $berlesKezdete,
                    $berlesVege,
                    $arazas,
                    $user,
                    $car,
                    $tavolsagAdatok['parkolasokAranyok']
                );
            }
            ## 6. Idők validálása
            $parkingRecordsParkolasIdo = floor(array_sum(array_column($parkingRecords, 'parking_minutes')));
            $parkolasokIdo = floor(array_sum(array_column($parkolasok, 'parking_minutes')));
            $teljesParkolasIdo = $parkingRecordsParkolasIdo + $parkolasokIdo;
            if ($teljesParkolasIdo == 0 && $vezetesIdo == 0) {
                continue;
            }

            ## 7. Idővalidáció ellenőrzése
            $timeValidation = CarUserRentParkingFactory::new()->userFullTimeRentValidation(
                $berlesKezdete,
                $car,
                $berlesVege,
                $arazas,
                $vezetesIdo,
                $parkolasok,
                $user
            );

            ## 8. Frissített értékek
            $parkolasok = $timeValidation['parking'];
            $vezetesIdo = $timeValidation['driving'];

            ## 9. Végső állapotok frissítése és ellenőrzése

            $vegsoAllapot = $this->carRefreshService->frissitesTavolsagUtan($car, $megtettTavolsag);

            $this->carRefreshService->ellenorizToltottseg($car, $vegsoAllapot['uj_toltes_szazalek']);

            if (
                $vegsoAllapot === null ||
                $vegsoAllapot['uj_toltes_szazalek'] < $this->carRefreshService->chargingCategories[$car->category_id]['min_toltes'] - 5
            ) {
                continue;
            }
            break;
        } while (true);


        return [
            'car_id' => $car->id,
            'category_id' => $car->category_id,
            'user_id' => $user->id,
            'start_percent' => $nyitasToltesSzazalek,
            'start_kw' => $nyitasToltesKw,
            'end_percent' => $vegsoAllapot['uj_toltes_szazalek'],
            'end_kw' => round($vegsoAllapot['uj_toltes_kw'], 1),
            'rent_start' => $berlesKezdete,
            'rent_close' => $berlesVege,
            'distance' => $megtettTavolsag,
            'parking_minutes' => $teljesParkolasIdo,
            'driving_minutes' => $vezetesIdo,
            'rental_cost' => $this->berlesVegosszegSzamolas(
                $arazas,
                $user,
                $megtettTavolsag,
                $car->category_id,
                $berlesIdotartam,
                $berlesKezdete,
                $berlesVege,
                $parkolasok,
                $vezetesIdo,

            ),
            'invoice_date' => now(),
            'rentstatus' => 2,
            ## Extra adatok a parkolásokhoz és töltésekhez
            'parkolasok' => $parkolasok,
            'chargeData' => $chargeData,
            'parkingRecords' => $parkingRecords,
        ];
    }
    private function berlesKezdete(): DateTime
    {
        return fake()->dateTimeBetween('-180 days', 'now');
    }
    public function berlesIdotartama($autoKategoria): int
    {
        # 5% eséllyel hosszútávra | [1-3, 3-5, 3-15 napra]
        if (random_int(1, 12) == 1) {
            $idotartam = match ($autoKategoria) {
                1, 3 => match (random_int(1, 2)) {              ## 2-5 nap (1440-14.400 perc)
                    1 => random_int(2880, 7200),
                    2 => random_int(2880, 14400),
                },
                2 => match (random_int(1, 2)) {                 ## 1-3 nap (1440-4320 perc)
                    1 => random_int(1440, 4320),
                    2 => random_int(2880, 4320)
                },
                4 => match (random_int(1, 2)) {                 ## 2-3 nap (2880-4320 perc)
                    1 => random_int(2880, 4320),
                    2 => random_int(2880, 4320)
                },

                5 => match (random_int(1, 2)) {                 ## 2-10 nap (1440-14.400 perc)
                    1 => random_int(2880, 14400),
                    2 => random_int(2880, 14400)
                },
            };
            return $idotartam;
        } else {
            $idotartam = match ($autoKategoria) {
                1, 3 =>
                match (random_int(1, 8)) {     ## [1-8] között random 'oszt.
                    1 => random_int(2, 10),      ## 2-10 perc
                    2 => random_int(10, 20),     ## 10-20 perc
                    3 => random_int(30, 40),     ## 30-40 perc
                    4 => random_int(40, 60),     ## 40-60 perc
                    5 => random_int(60, 80),     ## 60-80 perc
                    6 => random_int(60, 120),    ## 60-120 perc
                    7 => random_int(120, 360),   ## 2-6 óra (120-360 perc)
                    8 => random_int(360, 720),   ## 6-12 óra (360-720 perc)
                },
                2, 4 => match (random_int(1, 4)) {
                    1 => random_int(60, 180),    ## 1-3 óra
                    2 => random_int(180, 360),   ## 3-6 óra
                    3 => random_int(360, 720),   ## 6-12 óra
                    4 => random_int(720, 1440),  ## 12-24 óra
                },
                5 => random_int(2880, 7200),     ## 2-4 napos bérlések CSAK ERRE!      
            };
            return $idotartam;
        }
    }
    public function megtettTavolsag($car, $berlesKezdete, $berlesVege): array
    {
        $egyKwKilometerben = round(($car->fleet->driving_range / $car->fleet->motor_power), 2);
        $maxtavJelenlegiToltessel = $egyKwKilometerben * $car->power_kw;
        $times = $this->calculateTimes($berlesKezdete, $berlesVege);

        $vezetesIdo = 0;
        $parkolasokDarabszam = 0;
        $parkolasMaxIdo = 0;
        $parkolasokAranyok = [];

        # <= 15 perc: csak vezetés
        if ($times['minutes'] <= 15) {
            $megtettTavolsag = min(floor($times['minutes'] / 2), $maxtavJelenlegiToltessel);
            $vezetesIdo = $megtettTavolsag * 2;
        }
        # 16-30 perc: vezetés + 1 rövid parkolás
        elseif ($times['minutes'] <= 30) {
            $maxLehetsegesTav = min(floor($times['minutes'] / 2), $maxtavJelenlegiToltessel);
            $megtettTavolsag = random_int(1, $maxLehetsegesTav);
            $vezetesIdo = $megtettTavolsag * 2;
            if ($times['minutes'] - $vezetesIdo >= 5) {
                $parkolasokDarabszam = 1;
                $parkolasMaxIdo = $times['minutes'] - $vezetesIdo;
            }
        }
        # 31 p && 3 óra: vezetés + 2 parkolás
        elseif ($times['minutes'] <= 180) {
            $maxLehetsegesTav = min(floor($times['minutes'] / 2), $maxtavJelenlegiToltessel);
            if ($maxLehetsegesTav < 15) {
                $megtettTavolsag = $maxLehetsegesTav;
            } else {
                $megtettTavolsag = random_int(15, $maxLehetsegesTav);
            }
            $vezetesIdo = $megtettTavolsag * 2;
            $parkolasokDarabszam = random_int(1, 2);
            $osszesParkolas = $times['minutes'] - $vezetesIdo;
            $parkolasokAranyok = [
                'elso' => floor($osszesParkolas * 0.6),
                'masodik' => floor($osszesParkolas * 0.4)
            ];
        }
        # 3 óra felett: 3-5 parkolás
        else {
            $maxLehetsegesTav = min(floor($times['minutes'] / 2), $maxtavJelenlegiToltessel);
            if ($maxLehetsegesTav < 15) {
                $megtettTavolsag = max(1, $maxLehetsegesTav - 3);
            } else {
                $minTav = max(15, floor($maxLehetsegesTav * 0.3));
                $megtettTavolsag = random_int($minTav, $maxLehetsegesTav);
            }
            $vezetesIdo = $megtettTavolsag * 2;
            $parkolasokDarabszam = fake()->numberBetween(3, 5);
            $osszesParkolas = $times['minutes'] - $vezetesIdo;
            $parkolasokAranyok = [
                'elso' => floor($osszesParkolas * 0.5),
                'masodik' => floor($osszesParkolas * 0.3),
                'harmadik' => floor($osszesParkolas * 0.2)
            ];
        }

        return [
            'megtettTavolsag' => $megtettTavolsag,
            'vezetesIdo' => $vezetesIdo,
            'parkolasokDarabszam' => $parkolasokDarabszam,
            'parkolasMaxIdo' => $parkolasMaxIdo,
            'parkolasokAranyok' => $parkolasokAranyok
        ];
    }
    public function longTermRentPriceCalculation($autoKategoria, $arazas, $berlesKezdete, $berlesVege)
    {
        $idoKulonbsegOra = floor(($berlesVege->getTimestamp() - $berlesKezdete->getTimestamp()) / 3600);
        $days = ceil($idoKulonbsegOra / 24);
        $napidij = Dailyrental::where('prices_id', $arazas->id)
            ->where('category_class', $autoKategoria)
            ->get();

        $napiDijTomb = $napidij->pluck('price')->toArray();
        $tobbNaposDij = $napiDijTomb[$days - 2] ?? end($napiDijTomb);
        return ($tobbNaposDij + $arazas->km_fee + $arazas->rental_start);
    }

    public function firstDailyRentInMonth(DateTime $berlesKezdete, Price $arazas, User $user)
    {
        $currentMonth = $berlesKezdete->format('Y-m');
        $vanMarKedvezmenyesBerles = Renthistory::where('user_id', $user->id)
            ->where('rent_start', 'like', "{$currentMonth}%")
            ->where('rental_cost', '=', $arazas->daily_fee)
            ->first();

        return (bool) $vanMarKedvezmenyesBerles;
    }
    private function calculateTimes(DateTime $start, DateTime $end): array
    {
        $totalSeconds = $end->getTimestamp() - $start->getTimestamp();
        return [
            'seconds' => $totalSeconds,
            'minutes' => floor($totalSeconds / 60),
            'hours' => floor($totalSeconds / 3600),
            'days' => floor($totalSeconds / (24 * 3600)),
            'remainingHours' => floor(($totalSeconds % (24 * 3600)) / 3600),
            'remainingMinutes' => floor(($totalSeconds % 3600) / 60)
        ];
    }
    private function furgonokArazasMeghatarozas(
        int $autoKategoria,
        int $berlesIdotartam,
        Price $arazas,
        float $berlesInditasa,
        DateTime $berlesKezdete,
        DateTime $berlesVege,
        float $napiKmLimitTullepes,
        int $maradekOrak,
        int $teljesNapok
    ): float {
        ## 2-es és 4-es kategória speciális kezelése
        if (in_array($autoKategoria, [2, 4])) {
            if ($berlesIdotartam <= 180) {
                return min($arazas->three_hour_fee + $berlesInditasa, $arazas->daily_fee + $berlesInditasa);
            } elseif ($berlesIdotartam <= 360) {
                return min($arazas->six_hour_fee + $berlesInditasa, $arazas->daily_fee + $berlesInditasa);
            } elseif ($berlesIdotartam <= 720) {
                return min($arazas->twelve_hour_fee + $berlesInditasa, $arazas->daily_fee + $berlesInditasa);
            }
        }

        ## 5-ös kategória speciális kezelése
        if ($autoKategoria == 5) {
            if ($teljesNapok < 2) {
                $alapOsszeg = $this->longTermRentPriceCalculation($autoKategoria, $arazas, $berlesKezdete, $berlesVege);
                return min($alapOsszeg, $arazas->daily_fee + $berlesInditasa);
            }
        }
        ## Alap többnapos számítás speciális kategóriákra
        $alapdij = $this->longTermRentPriceCalculation($autoKategoria, $arazas, $berlesKezdete, $berlesVege);
        ## Maradék órák kezelése
        if ($maradekOrak > 0) {
            $reszidosDij = $this->reszidosDijSzamitas($maradekOrak, 0, $arazas);
            $pluszNapiDij = min($reszidosDij, $arazas->daily_fee);
            $alapdij += $pluszNapiDij;
        }
        return $alapdij + $napiKmLimitTullepes + $berlesInditasa;
    }

    private function reszidosDijSzamitas(int $maradekOrak, int $maradekPercek, Price $arazas): float
    {
        if ($maradekOrak <= 3 && $arazas->three_hour_fee !== null) {
            return $arazas->three_hour_fee;
        } elseif ($maradekOrak <= 6 && $arazas->six_hour_fee !== null) {
            return $arazas->six_hour_fee;
        } elseif ($maradekOrak <= 12 && $arazas->twelve_hour_fee !== null) {
            return $arazas->twelve_hour_fee;
        } else {
            return ($maradekOrak * 60 + $maradekPercek) * $arazas->driving_minutes;
        }
    }
    public function berlesVegosszegSzamolas(
        Price $arazas,
        User $user,
        int $tavolsag,
        int $autoKategoria,
        int $berlesIdotartam,
        DateTime $berlesKezdete,
        DateTime $berlesVege,
        array $parkolasok,
        int $vezetesIdo
    ): int {
        ## 1. Időszámítás
        $times = $this->calculateTimes($berlesKezdete, $berlesVege);

        ## 2. Parkolási idő és költség
        $teljesParkolasIdo = empty($parkolasok) ? 0 :
            floor(array_sum(array_column($parkolasok, 'parking_minutes')));
        $parkolasDijOsszeg = empty($parkolasok) ? 0 :
            floor(array_sum(array_column($parkolasok, 'total_cost')));

        ## 3. Alapdíjak
        $berlesInditasa = $arazas->rental_start;
        $vezPercDij = $arazas->driving_minutes;
        $kmDij = $arazas->km_fee;
        $napidij = $arazas->daily_fee;

        ## 4. Vezetési díj számítása a tényleges vezetési idővel
        $vezetesOsszeg = floor($vezetesIdo * $vezPercDij);

        ## 5. Km túllépés számítása
        $kmTullepes = max(0, $tavolsag - ($arazas->daily_km_limit * max(1, $times['days'])));
        $napiKmLimitTullepes = floor($kmTullepes * $kmDij);

        ## 6. Alap végösszeg számítása
        $alapOsszeg = $berlesInditasa + $vezetesOsszeg + $parkolasDijOsszeg + $napiKmLimitTullepes;

        ## 7. Részidős díj számítása ha van maradék óra/perc
        $reszidosDij = 0;
        if ($times['remainingHours'] > 0 || $times['remainingMinutes'] > 0) {
            $reszidosDij = $this->reszidosDijSzamitas($times['remainingHours'], $times['remainingMinutes'], $arazas);
        }

        ## 8. VIP felhasználók kezelése
        if ($user->sub_id == 4 && !$this->firstDailyRentInMonth($berlesKezdete, $arazas, $user) && !in_array($autoKategoria, [2, 4, 5])) {
            if ($times['days'] < 1) {
                ## Egy napnál rövidebb bérlés
                $kedvezmenyesNapidij = floor($napidij * 0.5);
                return min($alapOsszeg, $kedvezmenyesNapidij + $berlesInditasa + $napiKmLimitTullepes);
            } else {
                ## Többnapos bérlés: első nap 50%-os kedvezmény
                $longTermPrice = $this->longTermRentPriceCalculation($autoKategoria, $arazas, $berlesKezdete, $berlesVege);
                return floor(($napidij * 0.5) + ($longTermPrice - $napidij)) + $berlesInditasa + $napiKmLimitTullepes;
            }
        }

        ## 9. Speciális kategóriák kezelése (2, 4, 5)
        if (in_array($autoKategoria, [2, 4, 5])) {
            return $this->furgonokArazasMeghatarozas(
                $autoKategoria,
                $times['minutes'],
                $arazas,
                $berlesInditasa,
                $berlesKezdete,
                $berlesVege,
                $napiKmLimitTullepes,
                $times['remainingHours'],
                $times['days']
            );
        }

        ## 10. Normál bérlés kezelése
        if ($times['days'] < 1) {
            ## Egy napnál rövidebb bérlés
            $egynaposAr = $napidij + $berlesInditasa + $napiKmLimitTullepes;
            $percdijasAr = $alapOsszeg;
            return floor(min($percdijasAr, $egynaposAr));
        } else {
            ## Többnapos bérlés
            $tobbnaposAr = $this->longTermRentPriceCalculation($autoKategoria, $arazas, $berlesKezdete, $berlesVege);

            ## Ha van maradék idő, ellenőrizzük, hogy megéri-e a következő teljes napot számolni
            if ($reszidosDij > 0) {
                $kovetkezoNapAr = $this->longTermRentPriceCalculation(
                    $autoKategoria,
                    $arazas,
                    $berlesKezdete,
                    (clone $berlesKezdete)->modify('+' . ($times['days'] + 1) . ' days')
                );

                if ($reszidosDij > ($kovetkezoNapAr - $tobbnaposAr)) {
                    $tobbnaposAr = $kovetkezoNapAr;
                } else {
                    $tobbnaposAr += $reszidosDij;
                }
            }

            return floor($tobbnaposAr + $berlesInditasa + $napiKmLimitTullepes);
        }
    }
}
