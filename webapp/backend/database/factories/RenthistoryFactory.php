<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\Dailyrental;
use App\Models\Price;
use App\Models\Renthistory;
use App\Models\User;
use App\Policies\CarRefreshService;
use App\Rules\DailyRentalBonusRule;
use App\Rules\PlantTreeCampaignRule;
use App\Services\BonusMinutesService;
use App\Services\BonusRuleService;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

class RenthistoryFactory extends Factory
{
    protected $model = Renthistory::class;
    private CarRefreshService $carRefreshService;
    private BonusRuleService $bonusRuleService;
    private BonusMinutesService $bonusMinutesService;
    private DailyRentalBonusRule $dailyRentalBonusRule;
    protected PlantTreeCampaignRule $plantTreeCampaignRule;

    public function __construct()
    {
        parent::__construct();
        parent::__construct();
        $this->carRefreshService = new CarRefreshService();
        $this->bonusMinutesService = new BonusMinutesService();
        $this->dailyRentalBonusRule = new DailyRentalBonusRule();
        $this->bonusRuleService = new BonusRuleService($this->bonusMinutesService, $this->dailyRentalBonusRule);
        $this->plantTreeCampaignRule = new PlantTreeCampaignRule();

        ## Szabályok regisztrálása
        $this->bonusRuleService->registerRule($this->plantTreeCampaignRule);
        $this->states = collect();
    }
    public function definition(): array
    {
        try {
            $car = Car::with('fleet')->where('status', 1)->inRandomOrder()->first();
            $user = User::inRandomOrder()->first();
            $arazas = Price::where('category_class', $car->category_id)
                ->where('sub_id', $user->sub_id)
                ->first();

            $berlesKezdete = $this->berlesKezdete();
            $berlesIdotartam = $this->berlesIdotartama($car->category_id);
            $berlesVege = (clone $berlesKezdete)->modify("+{$berlesIdotartam} minutes");
            $times = $this->calculateTimes($berlesKezdete, $berlesVege);

            ## Kezdeti töltöttségi állapot
            $nyitasToltesSzazalek = $car->power_percent;
            $nyitasToltesKw = $car->fleet->motor_power * ($nyitasToltesSzazalek / 100);

            $tavolsagAdatok = $this->megtettTavolsag($car, $times);
            $megtettTavolsag = $tavolsagAdatok['megtettTavolsag'];
            $vezetesIdo = $tavolsagAdatok['vezetesIdo'];
            $parkolasokDarabszam = $tavolsagAdatok['parkolasokDarabszam'];
            $vegsoAllapot = $this->carRefreshService->frissitesTavolsagUtan($car, $megtettTavolsag);

            $parkingRecords = [];
            $parkolasok = [];
            $chargeData = [];
            $teljesParkolasIdo = 0;
            $toltesekGeneralas = 0;
            $maxToltesekSzama = 5;
            $osszesParkolasEsemeny = [];

            while (
                $toltesekGeneralas < $maxToltesekSzama &&
                CarUserrentChargeFactory::new()->kellHozzaTolteniAutot($times['minutes'], $megtettTavolsag, $car)
            ) {
                $currentChargeData = CarUserrentChargeFactory::new()->generaljToltest($car, $user, $berlesKezdete, $times);
                if (
                    $currentChargeData !== null &&
                    is_array($currentChargeData) &&
                    $this->isValidChargeData($currentChargeData)
                ) {
                    $toltesekGeneralas++;
                    $teljesParkolasIdo += $currentChargeData['charging_time'];
                    ## Parkolási esemény a töltéshez
                    $parkingRecords[] = [
                        'kezd' => $currentChargeData['charging_start_date']->format('Y-m-d H:i:s'),
                        'veg' => $currentChargeData['charging_end_date']->format('Y-m-d H:i:s'),
                        'parking_minutes' => $currentChargeData['charging_time'],
                        'total_cost' => in_array($car->category_id, [4, 5]) ?
                            $currentChargeData['charging_time'] * 90 :
                            90 * $currentChargeData['charging_time']
                    ];

                    $chargeData[] = $currentChargeData;

                    ## Bérlési idő frissítése a töltés miatt
                    $berlesVege = (clone $berlesVege)->modify("+{$currentChargeData['charging_time']} minutes");
                    $times = $this->calculateTimes($berlesKezdete, $berlesVege);
                }
            }

            ## Parkolások generálása
            if ($parkolasokDarabszam > 0 && !empty($tavolsagAdatok['parkolasokAranyok'])) {
                $parkolasok = CarUserRentParkingFactory::new()->generaltParkolasok(
                    $berlesKezdete,
                    $berlesVege,
                    $arazas,
                    $user,
                    $car,
                    $tavolsagAdatok['parkolasokAranyok']
                );
            }

            $parkingRecordsParkolasIdo = !empty($parkingRecords) ?
                array_sum(array_column($parkingRecords, 'parking_minutes')) : 0;
            $parkolasokIdo = !empty($parkolasok) ?
                array_sum(array_column($parkolasok, 'parking_minutes')) : 0;

            $teljesParkolasIdo = $parkingRecordsParkolasIdo + $parkolasokIdo;
            $osszesParkolasEsemeny = array_merge($parkolasok, $parkingRecords);

            ## Ellenőrizzük, hogy a parkolási/vezetési idők megfelelnek-e a teljes bérlési időnek
            $timeValidation = CarUserRentParkingFactory::new()->userFullTimeRentValidation(
                $berlesKezdete,
                $car,
                $berlesVege,
                $arazas,
                $vezetesIdo,
                $osszesParkolasEsemeny,
                $user
            );
            ## A timeValidation ['parking'] a parkolási percek száma! NEM a tömb!
            $teljesParkolasIdo = $timeValidation['parking'];
            $vezetesIdo = $timeValidation['driving'];

            ## Ellenőrzés, hogy a teljes idő kijön-e
            $totalRentMinutes = $times['minutes'];
            $totalActivityMinutes = $teljesParkolasIdo + $vezetesIdo;

            ## Ha eltérés van, korrigálás
            if ($totalActivityMinutes != $totalRentMinutes) {
                ## A különbséget a vezetési időhöz
                $vezetesIdo += ($totalRentMinutes - $totalActivityMinutes);
            }
            $vegsoAllapot = $this->carRefreshService->frissitesTavolsagUtan($car, $megtettTavolsag);
            $this->carRefreshService->ellenorizToltottseg($car, $vegsoAllapot['uj_toltes_szazalek']);

            ##############################
            ## Bónusz perces mókolgatás ##
            $rental_cost = null;
            $endPercent = round($vegsoAllapot['uj_toltes_szazalek'], 2);
            $endKw = round($vegsoAllapot['uj_toltes_kw'] ?? $nyitasToltesKw, 1);

            $fizetendoVezetesiIdo = $vezetesIdo;
            if ($this->plantTreeCampaignRule->isEligible($user, []) && $user->bonus_minutes > 0 && $vezetesIdo < 240) { ## cirka 4 óránál már a VIP-nek is napidíj lesz
                ## 10% esély a bónusz percek felhasználására
                if (random_int(1, 10) < 2) {
                    $felhasznaltPercek = $this->bonusMinutesService->useBonusMinutes($user, $vezetesIdo);
                    $fizetendoVezetesiIdo = $vezetesIdo - $felhasznaltPercek;
                }
            }
            if ($this->plantTreeCampaignRule->isEligible($user, [])) {
                $rental_cost = $this->berlesVegosszegSzamolas(
                    $arazas,
                    $user,
                    $megtettTavolsag,
                    $car->category_id,
                    $berlesIdotartam,
                    $berlesKezdete,
                    $berlesVege,
                    $teljesParkolasIdo,
                    $fizetendoVezetesiIdo
                );
            } else {
                $rental_cost = $this->berlesVegosszegSzamolas(
                    $arazas,
                    $user,
                    $megtettTavolsag,
                    $car->category_id,
                    $berlesIdotartam,
                    $berlesKezdete,
                    $berlesVege,
                    $teljesParkolasIdo,
                    $vezetesIdo
                );
            }
            $isDailyRental = $rental_cost['napidijas'] ?? false;
            if ($this->plantTreeCampaignRule->isEligible($user, $isDailyRental)) {
                $this->bonusMinutesService->addDrivingMinutes($user, $vezetesIdo);
            }
            ## Bónuszok (szabályok) alkalmazása a [bérlés lezárása után]!!
            if ($this->plantTreeCampaignRule->isEligible($user, $isDailyRental) || $isDailyRental) {
                $context = [
                    'is_daily_rental' => $isDailyRental,
                    'end_percent' => $endPercent,
                    'driving_minutes' => $vezetesIdo,
                    'rental_duration' => $berlesIdotartam
                ];
                ## Végig megyünk az összes 
                ## olyan szabályon, amire a felhasználó jogosult lehet.
                $this->bonusRuleService->applyEligibleRules($user, $context);
            }
            return [
                'car_id' => $car->id,
                'category_id' => $car->category_id,
                'user_id' => $user->id,
                'start_percent' => round($nyitasToltesSzazalek, 2),
                'start_kw' => round($nyitasToltesKw, 1),
                'end_percent' => $endPercent,
                'end_kw' => $endKw,
                'rent_start' => $berlesKezdete,
                'rent_close' => $berlesVege,
                'distance' => $megtettTavolsag,
                'parking_minutes' => $teljesParkolasIdo,
                'driving_minutes' => $vezetesIdo,
                'rental_cost' => $rental_cost['osszeg'], ## Mivel visszaadjuk azt is, hogy napidíjas volt-e a számítás.
                'invoice_date' => now(),
                'rentstatus' => 2,
                'osszesParkolasEsemeny' => $osszesParkolasEsemeny,
                'chargeData' => $chargeData,
            ];
        } catch (Exception $e) {
            Log::error('RenthistoryFactory-ben hiba került a számításba: ' . $e->getMessage());
            Log::error('A hiba keletkezésének pontos helye: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    private function isValidChargeData(array $chargeData): bool
    {
        return
            isset($chargeData['charging_start_date']) &&
            isset($chargeData['charging_end_date']) &&
            isset($chargeData['charging_time']) &&
            $chargeData['charging_time'] > 0 &&
            $chargeData['charging_start_date'] instanceof DateTime &&
            $chargeData['charging_end_date'] instanceof DateTime;
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

    public function megtettTavolsag($car, $times): array
    {
        $egyKwKilometerben = round(($car->fleet->driving_range / $car->fleet->motor_power), 2);
        $maxtavJelenlegiToltessel = $egyKwKilometerben * $car->power_kw;
        $vezetesIdo = null;
        $parkolasokDarabszam = null;
        $parkolasMaxIdo = null;
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
        int $teljesNapok,
        int $parkolasok,
    ): float {
        ## Parkolási díj minden kategóriára egységesen
        $parkolasiDij = $parkolasok * $arazas->parking_minutes;
        ## 2-es és 4-es kategória speciális kezelése
        if ($autoKategoria == 5) {
            if ($berlesIdotartam <= 180) {
                return min($arazas->three_hour_fee + $berlesInditasa, $arazas->daily_fee + $berlesInditasa)
                    + $napiKmLimitTullepes + $parkolasiDij;
            } elseif ($berlesIdotartam <= 360) {
                return min($arazas->six_hour_fee + $berlesInditasa, $arazas->daily_fee + $berlesInditasa)
                    + $napiKmLimitTullepes + $parkolasiDij;
            } elseif ($berlesIdotartam <= 720) {
                return min($arazas->twelve_hour_fee + $berlesInditasa, $arazas->daily_fee + $berlesInditasa)
                    + $napiKmLimitTullepes + $parkolasiDij;
            } else {
                $hosszuBerlesAr = $arazas->daily_fee * max(1, $teljesNapok);
                if ($maradekOrak > 0 || ($berlesIdotartam % 1440) > 0) {
                    $reszidosDij = $this->reszidosDijSzamitas($maradekOrak, 0, $arazas);
                    $hosszuBerlesAr = min($hosszuBerlesAr + $reszidosDij, $hosszuBerlesAr + $arazas->daily_fee);
                }

                return $hosszuBerlesAr + $berlesInditasa + $napiKmLimitTullepes + $parkolasiDij;
            }
        }
        ## 5-ös kategória speciális kezelése
        if (in_array($autoKategoria, [2, 4])) {
            if ($teljesNapok < 2) {
                $alapOsszeg = $this->longTermRentPriceCalculation($autoKategoria, $arazas, $berlesKezdete, $berlesVege);
                return min($alapOsszeg, $arazas->daily_fee + $berlesInditasa) + $napiKmLimitTullepes + $parkolasiDij;
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
        return $alapdij + $napiKmLimitTullepes + $berlesInditasa + $parkolasiDij;
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
        int $parkolasok,
        int $vezetesIdo
    ): array {
        ## 1. Időszámítás
        $times = $this->calculateTimes($berlesKezdete, $berlesVege);
        ## 3. Alapdíjak
        $berlesInditasa = $arazas->rental_start;
        $vezPercDij = $arazas->driving_minutes;
        $kmDij = $arazas->km_fee;
        $napidij = $arazas->daily_fee;
        $parkolsPercdij = $arazas->parking_minutes;

        ## 4. Vezetési díj számítása a tényleges vezetési idővel
        $vezetesOsszeg = floor($vezetesIdo * $vezPercDij);

        ## 5. Km túllépés számítása
        ## Mivel a km limit lehet mondjuk 300 is, de megtett 68-at, akkor
        ## 68-300 >> Mínuszba menne, és a max-szal nagyobb a 0, mint 0 Ft költség!!!
        $kmTullepes = max(0, $tavolsag - ($arazas->daily_km_limit * max(1, $times['days'])));
        $napiKmLimitTullepes = floor($kmTullepes * $kmDij);

        ## 6. Alap végösszeg számítása
        $alapOsszeg = $berlesInditasa + $vezetesOsszeg + $parkolasok * $parkolsPercdij + $napiKmLimitTullepes;

        ## 7. Részidős díj számítása ha van maradék óra/perc
        $reszidosDij = 0;
        if ($times['remainingHours'] > 0 || $times['remainingMinutes'] > 0) {
            $reszidosDij = $this->reszidosDijSzamitas($times['remainingHours'], $times['remainingMinutes'], $arazas);
        }

        ## 8. VIP felhasználók kezelése
        if ($user->sub_id == 4 && $user->vip_discount && !in_array($autoKategoria, [2, 4, 5])) {
            if ($times['days'] < 1) {
                ## Egy napnál rövidebb VAGY 1 napos a bérlés
                $kedvezmenyesNapidij = round($napidij * 0.5);
                $napidijTeljesKoltseg = $kedvezmenyesNapidij + $berlesInditasa + $napiKmLimitTullepes;
                if ($napidijTeljesKoltseg < $alapOsszeg) {
                    $user->vip_discount = false;
                    $user->save();
                    return ['osszeg' => $napidijTeljesKoltseg, 'napidijas' => true];
                }
            } else {
                ## Többnapos bérlés: első nap 50%-os kedvezmény
                $longTermPrice = $this->longTermRentPriceCalculation($autoKategoria, $arazas, $berlesKezdete, $berlesVege);
                $napidijTeljesKoltseg = round(($napidij * 0.5) + ($longTermPrice - $napidij)) + $berlesInditasa + $napiKmLimitTullepes;
                $user->vip_discount = false;
                $user->save();
                return ['osszeg' => $napidijTeljesKoltseg, 'napidijas' => true];
            }
        }

        ## 9. Speciális kategóriák kezelése (2, 4, 5)
        ## 9. Speciális kategóriák kezelése (2, 4, 5)
        if (in_array($autoKategoria, [2, 4, 5])) {
            return [
                'osszeg' => $this->furgonokArazasMeghatarozas(
                    $autoKategoria,
                    $times['minutes'],
                    $arazas,
                    $berlesInditasa,
                    $berlesKezdete,
                    $berlesVege,
                    $napiKmLimitTullepes,
                    $times['remainingHours'],
                    $times['days'],
                    $parkolasok  // Add át a parkolások számát is
                ),
                'napidijas' => false
            ];
        }


        ## 10. "Normál bérlés" kezelése
        if ($times['days'] < 1) {
            ## Egy napnál rövidebb bérlés
            $egynaposAr = $napidij + $berlesInditasa + $napiKmLimitTullepes;
            if ($egynaposAr < $alapOsszeg) {
                return ['osszeg' => $egynaposAr, 'napidijas' => true];
            } else {
                return ['osszeg' => $alapOsszeg, 'napidijas' => false];
            }
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

            return [
                'osszeg' => $tobbnaposAr + $berlesInditasa + $napiKmLimitTullepes,
                'napidijas' => true,
            ];
        }
    }
}
