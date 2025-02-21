<?php

namespace Tests\Unit\RentHistoryFunctions;

use App\Models\Car;
use App\Models\Category;
use App\Models\Dailyrental;
use App\Models\Equipment;
use App\Models\Fleet;
use App\Models\Person;
use App\Models\Price;
use App\Models\User;
use Database\Factories\CarUserRentParkingFactory;
use DateTime;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ParkingFactoryTest extends TestCase
{
    use DatabaseTransactions;

    private CarUserRentParkingFactory $parkingFactory;
    private Car $testCar;
    private User $testUser;
    private Price $testPrice;
    private DateTime $startDate;
    private DateTime $endDate;
    private Dailyrental $testDailyRental;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parkingFactory = new CarUserRentParkingFactory();
        $this->setupTestData();
    }
    public function calculateTimes(DateTime $start, DateTime $end): array
    {
        $totalSeconds = $end->getTimestamp() - $start->getTimestamp();
        return [
            'seconds'          => $totalSeconds,
            'minutes'          => floor($totalSeconds / 60),
            'hours'            => floor($totalSeconds / 3600),
            'days'             => floor($totalSeconds / (24 * 3600)),
            'remainingHours'   => floor(($totalSeconds % (24 * 3600)) / 3600),
            'remainingMinutes' => floor(($totalSeconds % 3600) / 60)
        ];
    }
    private function setupTestData(): void
    {
        $testFleet = Fleet::where('manufacturer', 'VW')
            ->where('carmodel', 'e-up!')
            ->where('motor_power', 18)
            ->first();

        $testCategory = Category::where('category_class', $testFleet->id)
            ->where('motor_power', $testFleet->motor_power)
            ->firstOrFail();

        $testEquipment = Equipment::factory()->create([
            'reversing_camera'        => true,
            'lane_keep_assist'        => true,
            'adaptive_cruise_control' => true,
            'parking_sensors'         => true,
            'multifunction_wheel'     => true,
        ]);

        $this->testCar  = Car::factory()->create([
            'fleet_id'        => $testFleet->id,
            'category_id'     => $testCategory->id,
            'equipment_class' => $testEquipment->id,
            'status'          => 1,
            'power_percent'   => 50.00,
            'power_kw'        => 9.0,
            'estimated_range' => 67.5,
            "plate"           => fake()->regexify('[U-Z]{4}[1-9]{3}'),
            'odometer'        => 10_000,
            'manufactured'    => 2023,
        ]);

        $testPerson = Person::factory()->create([
            "person_password" => fake()->regexify('[0-9]{6}'),
            "id_card"         => fake()->unique()->regexify('[V-Z]{2}[1-9]{1}[0-9]{5}'),
            "firstname"       => fake()->firstName(),
            "lastname"        => fake()->lastName(),
            "birth_date"      => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            "phone"           => "+3630" . fake()->regexify('[0-9]{7}'),
            "email"           => fake()->unique()->lexify('??????????@gmail.com'),
        ]);

        $this->testUser = User::factory()->create([
            'person_id'       => $testPerson->id,
            'user_name'       => 'TestUserName' . fake()->regexify('[0-9]{5}'),
            'password'        => $testPerson->person_password,
            'password_2_4'    => $testPerson->person_password[1] . $testPerson->person_password[3],
            'account_balance' => 0,
            'sub_id'          => 1,

        ]);
        $this->testPrice = Price::where('category_class', $this->testCar->category_id)
            ->where('sub_id', $this->testUser->sub_id)
            ->firstOrFail();

        $this->testDailyRental = Dailyrental::where('prices_id', $this->testUser->sub_id)
            ->where('category_class', $this->testCar->category_id)
            ->firstOrFail();

        $this->startDate = new DateTime('2025-01-01 10:00:00');
        $this->endDate = new DateTime('2025-01-01 12:00:00');
    }

    public function test_parkolasok_generalasa_ot_perces_idovel_nem_lehet()
    {
        $mindegyikUres = true;

        for ($perc = 0; $perc <= 4; $perc++) {
            $parkolasokAranyok['elso'] = $perc;
            $parkolasok = $this->parkingFactory->generaltParkolasok(
                $this->startDate,
                $this->endDate,
                $this->testPrice,
                $this->testUser,
                $this->testCar,
                $parkolasokAranyok
            );
            $mindegyikUres = $mindegyikUres && empty($parkolasok);
        }
        $this->assertTrue($mindegyikUres, 'Nem kellene parkolást generálni 5 percnél rövidebb időtartamra');
    }
    public function test_parkolasok_generalasa_5_es_15_perc_kozott_valid()
    {
        $mindegyikValid = true;

        for ($perc = 5; $perc <= 15; $perc++) {
            $parkolasokAranyok['elso'] = $perc;
            $parkolasok = $this->parkingFactory->generaltParkolasok(
                $this->startDate,
                $this->endDate,
                $this->testPrice,
                $this->testUser,
                $this->testCar,
                $parkolasokAranyok
            );
            $mindegyikValid = $mindegyikValid && 
            count($parkolasok) === 1 &&
            $parkolasok[0]['parking_minutes'] === $perc &&
            array_key_exists('kezd', $parkolasok[0]) &&
            array_key_exists('veg', $parkolasok[0]) &&
            array_key_exists('total_cost', $parkolasok[0]);
        }
        $this->assertTrue($mindegyikValid, 'A parkolások generálása nem megfelelő 5 és 15 perc között');
    }
    public function test_teljes_parkolasi_ido_feldarabolása_tobb_0tol_5_idoszakra_helyesen_fut_e()
    {
        # <= 15 perc: NINCS parkolás
        # 16-30 perc: 1 rövid parkolás
        # 31 p && 3 óra: 2 parkolás
        # 3 óra felett: 3-5 parkolás

        $parkingCases = [
            # <= 15 perc: NINCS parkolás
            'nincs' => [
                'ido' => 15,
                'parkolasRekord' => [],
                'elvart' => 0
            ],
            # 16-30 perc: 1 rövid parkolás
            'egyParkolas' => [
                'ido' => 30,
                'parkolasRekord' => ['elso' => 10],
                'elvart' => 1
            ],
            # 31p - 3 óra: 2 parkolás
            'ketParkolas' => [
                'ido' => 120,
                'parkolasRekord' => [
                    'elso' => 20,
                    'masodik' => 15
                ],
                'elvart' => 2
            ],
            # 3 óra felett: 3-5 parkolás
            'haromParkolas' => [
                'ido' => 200,
                'parkolasRekord' => [
                    'elso' => 30,
                    'masodik' => 20,
                    'harmadik' => 15
                ],
                'elvart' => 3
            ],
            'negyParkolas' => [
                'ido' => 240,
                'parkolasRekord' => [
                    'elso' => 35,
                    'masodik' => 25,
                    'harmadik' => 20,
                    'negyedik' => 15
                ],
                'elvart' => 4
            ],
            'otParkolas' => [
                'ido' => 300,
                'parkolasRekord' => [
                    'elso' => 40,
                    'masodik' => 30,
                    'harmadik' => 25,
                    'negyedik' => 20,
                    'otodik' => 15
                ],
                'elvart' => 5
            ]
        ];
        foreach ($parkingCases as $nev => $oneCase) {
            $this->endDate = (clone $this->startDate)->modify("+{$oneCase['ido']} minutes");

            $parkolasok = $this->parkingFactory->generaltParkolasok(
                $this->startDate,
                $this->endDate,
                $this->testPrice,
                $this->testUser,
                $this->testCar,
                $oneCase['parkolasRekord']
            );

            $this->assertCount($oneCase['elvart'], $parkolasok, "A hibas lefutás esete: {$nev}");

            if ($oneCase['elvart'] > 0) {
                foreach ($parkolasok as $parkolas) {
                    $this->assertArrayHasKey('kezd', $parkolas);
                    $this->assertArrayHasKey('veg', $parkolas);
                    $this->assertArrayHasKey('parking_minutes', $parkolas);
                    $this->assertArrayHasKey('total_cost', $parkolas);

                    $kezdDate = new DateTime($parkolas['kezd']);
                    $vegDate = new DateTime($parkolas['veg']);

                    $this->assertGreaterThanOrEqual($this->startDate, $kezdDate, "Failed start time validation in {$nev}");
                    $this->assertLessThanOrEqual($this->endDate, $vegDate, "Failed end time validation in {$nev}");

                    $diff = $vegDate->diff($kezdDate);
                    $diffInMinutes = $diff->h * 60 + $diff->i;
                    $this->assertEquals(
                        $parkolas['parking_minutes'],
                        $diffInMinutes,
                        "Parkolási idő nem egyezik itt: {$nev}"
                    );
                }
            }
        }
    }
    public function test_parkolasi_koltseg_szamitasa_minden_elofizetesre_auto_kategóriara_es_napszakokra_validan()
    {
        # Összesen: 4*5*3 eset = 60 teszt.
        # Előfiz. sub_id-ja
        $subIds = [1, 2, 3, 4];
        # Minden car kategória
        $categoryIds = [1, 2, 3, 4, 5];
        $idopontok = [
            'nappali' => [
                'kezdes' => '2025-01-01 10:00:00',
                'berlesIdo' => 120,
                'nappali_perc' => 120,
                'ejszakai_perc' => 0
            ],
            'ejszakai' => [
                'kezdes' => '2025-01-01 23:00:00',
                'berlesIdo' => 120,
                'nappali_perc' => 0,
                'ejszakai_perc' => 120
            ],
            'vegyes' => [
                'kezdes' => '2025-01-01 21:00:00',
                'berlesIdo' => 180,
                'nappali_perc' => 60,
                'ejszakai_perc' => 120
            ]
        ];
        foreach ($subIds as $subId) {
            foreach ($categoryIds as $categoryId) {
                foreach ($idopontok as $idoszakNev => $idoszak) {
                    $testName = "sub_id_{$subId}_cat_{$categoryId}_{$idoszakNev}";

                    $this->testUser->sub_id = $subId;
                    $this->testUser->save();

                    $this->testCar->category_id = $categoryId;
                    $this->testCar->save();

                    $price = Price::where('category_class', $categoryId)
                        ->where('sub_id', $subId)
                        ->first();

                    $start = new DateTime($idoszak['kezdes']);
                    $end = (clone $start)->modify("+{$idoszak['berlesIdo']} minutes");

                    $parkolasok = [[
                        'kezd' => $start,
                        'veg' => $end,
                        'parking_minutes' => $idoszak['berlesIdo']
                    ]];

                    $totalcost = $this->parkingFactory->parkolasiKoltsegek(
                        $this->testUser,
                        $this->testCar,
                        $price,
                        $parkolasok
                    );

                    $percDij = $price->parking_minutes ?? 90;
                    $vartKoltseg = 0;

                    // Nappali díj számítás
                    if ($idoszak['nappali_perc'] > 0) {
                        $vartKoltseg += $idoszak['nappali_perc'] * $percDij;
                    }

                    // Éjszakai díj számítás - VIP előfizetőnél 1-3 kategóriában ingyenes
                    if (
                        $idoszak['ejszakai_perc'] > 0 &&
                        !($subId === 4 && in_array($categoryId, [1, 2, 3]))
                    ) {
                        $vartKoltseg += $idoszak['ejszakai_perc'] * $percDij;
                    }

                    $this->assertEquals(
                        $vartKoltseg,
                        $totalcost,
                        "Parkolási költség nem egyezik: {$testName}"
                    );

                    $napszakDarabolas = $this->parkingFactory->calculateDayNightMinutes($start, $end);
                    $this->assertEquals(
                        $idoszak['nappali_perc'],
                        $napszakDarabolas['day'],
                        "Nappali percek nem egyeznek: {$testName}"
                    );
                    $this->assertEquals(
                        $idoszak['ejszakai_perc'],
                        $napszakDarabolas['night'],
                        "Éjszakai percek nem egyeznek: {$testName}"
                    );
                }
            }
        }
    }
    public function test_user_full_time_rent_validation()
    {
        $testCases = [
            'normal_parkolas' => [
                'berles_idotartam' => 120,
                'vezetes_ido' => 60,
                'parkolasok' => [
                    ['parking_minutes' => 60, 'kezd' => '2025-01-01 10:00:00']
                ],
                'expected_driving' => 60,
                'expected_parking_count' => 1
            ],
            'hosszu_parkolas' => [
                'berles_idotartam' => 100,
                'vezetes_ido' => 20,
                'parkolasok' => [
                    ['parking_minutes' => 70, 'kezd' => '2025-01-01 10:00:00']
                ],
                # 100 perc * 0.4 >> 40 perc vezetés
                'expected_driving' => 40, 
                'expected_parking_count' => 1
            ],
            'tobb_parkolas' => [
                'berles_idotartam' => 180,
                'vezetes_ido' => 60,
                'parkolasok' => [
                    ['parking_minutes' => 40, 'kezd' => '2025-01-01 10:00:00'],
                    ['parking_minutes' => 50, 'kezd' => '2025-01-01 11:00:00']
                ],
                'expected_driving' => 90,
                'expected_parking_count' => 2
            ],
            'nincs_parkolas' => [
                'berles_idotartam' => 60,
                'vezetes_ido' => 60,
                'parkolasok' => [],
                'expected_driving' => 60,
                'expected_parking_count' => 0
            ]
        ];
        foreach ($testCases as $name => $testCase) {
            $berlesKezdete = new DateTime('2025-01-01 10:00:00');
            $berlesVege = (clone $berlesKezdete)->modify("+{$testCase['berles_idotartam']} minutes");

            $parkolasok = [];

            foreach ($testCase['parkolasok'] as $parkolas) {
                $lastIndex = count($parkolasok) - 1;
                $kezdIdo = new DateTime($parkolas['kezd']);
                $vegIdo = (clone $kezdIdo)->modify('+' . $parkolas['parking_minutes'] . ' minutes');

                $parkolasok[] = [
                    'kezd' => $kezdIdo->format('Y-m-d H:i:s'),
                    'veg' => $vegIdo->format('Y-m-d H:i:s'),
                    'parking_minutes' => $parkolas['parking_minutes'],
                    'total_cost' => $this->parkingFactory->parkolasiKoltsegek(
                        $this->testUser,
                        $this->testCar,
                        $this->testPrice,
                        [['kezd' => $kezdIdo->format('Y-m-d H:i:s'), 'veg' => $vegIdo->format('Y-m-d H:i:s'), 'parking_minutes' => $parkolas['parking_minutes']]]
                    )
                ];
            }

            $vezetesIdo = $testCase['vezetes_ido'];
            $result = $this->parkingFactory->userFullTimeRentValidation(
                $berlesKezdete,
                $this->testCar,
                $berlesVege,
                $this->testPrice,
                $vezetesIdo,
                $parkolasok,
                $this->testUser
            );

            $this->assertEquals($testCase['expected_driving'], $result['driving']);
            $this->assertCount($testCase['expected_parking_count'], $result['parking']);

            if ($testCase['expected_parking_count'] > 0) {
                $totalParkingMinutes = array_sum(array_column($result['parking'], 'parking_minutes'));
                $this->assertLessThanOrEqual(
                    round($testCase['berles_idotartam'] * 0.6),
                    $totalParkingMinutes,
                    "Parkolási idő túllépi a maximális 60%-ot: {$name}"
                );
            }
        }
    }
}
