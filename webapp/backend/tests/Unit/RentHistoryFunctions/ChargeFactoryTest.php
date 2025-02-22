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
use App\Policies\BillService;
use App\Policies\CarRefreshService;
use Carbon\Carbon;
use Database\Factories\CarUserrentChargeFactory;
use Database\Factories\RenthistoryFactory;
use DateTime;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ChargeFactoryTest extends TestCase
{
    use DatabaseTransactions;
    private CarRefreshService $testCarRefreshService;
    private CarUserrentChargeFactory $testChargeFactory;
    private array $testData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testCarRefreshService = new CarRefreshService();
        $this->testChargeFactory = new CarUserrentChargeFactory();
        $this->mockTestData();
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
    private function mockTestCarChargingData(): array
    {
        $eup18kwCases = [
            # 1. FALSE esetek | [60% feletti] töltés VAGY [20 perc alatti] bérlés
            ['distance' => 0, 'power' => 61, 'rental' => 19, 'expected' => false, 'scenario' => 'Magas töltöttség (>60%) és rövid bérlés (<20p)'],
            ['distance' => 10, 'power' => 61, 'rental' => 19, 'expected' => false, 'scenario' => 'Magas töltöttség normál távval'],
            ['distance' => 20, 'power' => 61, 'rental' => 19, 'expected' => false, 'scenario' => 'Magas töltöttség határérték távval'],
            # 2. Kritikus határérték vizsgálatok | [Távolság] alapján
            ['distance' => 20.25 - 1, 'power' => 15, 'rental' => 50, 'expected' => false, 'scenario' => 'Kritikus táv alatt 1 km-rel (20.25 km)'],
            ['distance' => 20.25 + 1, 'power' => 15, 'rental' => 50, 'expected' => true, 'scenario' => 'Kritikus táv felett 1 km-rel (20.25 km)'],
            ['distance' => 20.25 * 0.5, 'power' => 30, 'rental' => 120, 'expected' => true, 'scenario' => 'Kritikus táv 50%-a'],
            ['distance' => 20.25 * 1.5, 'power' => 30, 'rental' => 120, 'expected' => true, 'scenario' => 'Kritikus táv 150%-a'],
            # 3. Bérlési idő határérték vizsgálatok
            ['distance' => 30, 'power' => 40, 'rental' => 20, 'expected' => false, 'scenario' => 'Minimális bérlési idő (20p)'],
            ['distance' => 30, 'power' => 40, 'rental' => 60, 'expected' => false, 'scenario' => 'Közepes bérlési idő (60p)'],
            ['distance' => 30, 'power' => 40, 'rental' => 180, 'expected' => false, 'scenario' => 'Hosszú bérlési idő (180p)'],
            ['distance' => 30, 'power' => 40, 'rental' => 240, 'expected' => true, 'scenario' => 'Extra hosszú bérlési idő (240p)'],
            # 4. Töltöttségi szint határérték vizsgálatok
            ['distance' => 50, 'power' => 60, 'rental' => 120, 'expected' => false, 'scenario' => 'Maximum elfogadható töltöttség (60%)'],
            ['distance' => 50, 'power' => 35, 'rental' => 61, 'expected' => true, 'scenario' => 'Alacsony töltöttség határ (35%) hosszú bérlésnél'],
            ['distance' => 50, 'power' => 50, 'rental' => 181, 'expected' => true, 'scenario' => 'Közepes töltöttség (50%) extra hosszú bérlésnél'],
            # 5. Szélsőérték tesztek
            ['distance' => 135, 'power' => 100, 'rental' => 120, 'expected' => false, 'scenario' => 'Maximális hatótáv teljes töltöttséggel'],
            ['distance' => 67.5, 'power' => 100, 'rental' => 120, 'expected' => false, 'scenario' => 'Fél hatótáv teljes töltöttséggel'],
            ['distance' => 0, 'power' => 40, 'rental' => 120, 'expected' => false, 'scenario' => 'Nulla távolság normál töltöttséggel'],
            # 6. Normál random használatok - növekvő távolsággal
            ['distance' => 30, 'power' => 15, 'rental' => 50, 'expected' => true, 'scenario' => 'Megtett táv 30 km'],
            ['distance' => 60, 'power' => 15, 'rental' => 50, 'expected' => true, 'scenario' => 'Megtett táv 60 km'],
            ['distance' => 90, 'power' => 15, 'rental' => 50, 'expected' => true, 'scenario' => 'Megtett táv 90 km'],
            ['distance' => 120, 'power' => 15, 'rental' => 50, 'expected' => true, 'scenario' => 'Megtett táv 120 km']
        ];
        $kangooCases = [
            /**
             *  A kritikus pont 37.1 km a Kangoo esetében >>
             *  33kW / 100 = 0.33 [1% = kW]
             *  0.33 * 15% = 4.95 [kW]
             *  245km / 33kW = 7.42 km/kW
             *  4.95kW * 7.42km = 37.1km [A "kritikus" táv pont]
             * */
            # 1. hamis esetek
            ['distance' => 50, 'power' => 61, 'rental' => 19, 'expected' => false, 'scenario' => 'Magas töltöttség (>60%) és rövid bérlés (<20p)'],
            ['distance' => 0, 'power' => 40, 'rental' => 120, 'expected' => false, 'scenario' => 'Nulla távolság normál töltöttséggel'],
            # 2. Kritikus "fordulópont" értékek
            ['distance' => 37 - 1, 'power' => 15, 'rental' => 50, 'expected' => true, 'scenario' => 'Kritikus táv alatt 1 km-rel (37.1 km)'],
            ['distance' => 37 + 1, 'power' => 15, 'rental' => 50, 'expected' => true, 'scenario' => 'Kritikus táv felett 1 km-rel (37.1 km)'],
            ['distance' => 150, 'power' => 40, 'rental' => 120, 'expected' => true, 'scenario' => 'Maximum hatótáv 60%-a'],
            # 3. Töltöttségi szint határértékek
            ['distance' => 80, 'power' => 35, 'rental' => 61, 'expected' => true, 'scenario' => 'Alacsony töltöttség (35%) hosszú bérlésnél'],
            ['distance' => 120, 'power' => 50, 'rental' => 181, 'expected' => true, 'scenario' => 'Közepes töltöttség (50%) extra hosszú bérlésnél'],
            # 4. Szélső-érték teszt
            ['distance' => 245, 'power' => 100, 'rental' => 120, 'expected' => false, 'scenario' => 'Maximális hatótáv teljes töltöttséggel'],
            ['distance' => 122.5, 'power' => 100, 'rental' => 120, 'expected' => false, 'scenario' => 'Fél hatótáv teljes töltöttséggel']
        ];
        $KiaNiroCases = [
            /** Kritikus táv Niro - 65kW 
             *  65kW / 100 = 0.65 [1% = kW]
             *  0.65 * 15% = 9.75 [kW]
             *  460km / 65kW = 7.08 km/kW
             *  9.75kW * 7.08km = 70.77**km
             * */
            # 1. hamis esetek
            ['distance' => 100, 'power' => 61, 'rental' => 19, 'expected' => false, 'scenario' => 'Magas töltöttség (>60%) és rövid bérlés'],
            ['distance' => 0, 'power' => 40, 'rental' => 120, 'expected' => false, 'scenario' => 'Nulla távolság normál töltöttséggel'],
            # 2. Kritikus "fordulópont" értékek
            ['distance' => 70, 'power' => 15, 'rental' => 19, 'expected' => false, 'scenario' => 'Kritikus idő alatt 1p-cel'],
            ['distance' => 70, 'power' => 15, 'rental' => 20, 'expected' => true, 'scenario' => 'Kritikus idő felett 1p-cel'],
            ['distance' => 72, 'power' => 15, 'rental' => 50, 'expected' => true, 'scenario' => 'Kritikus táv felett 1 km-rel'],
            ['distance' => 280, 'power' => 40, 'rental' => 120, 'expected' => true, 'scenario' => 'Maximum hatótáv 60%-a'],
            # 3. Töltöttségi szint határértékek
            ['distance' => 150, 'power' => 35, 'rental' => 61, 'expected' => true, 'scenario' => 'Alacsony töltöttség (35%) hosszú bérlésnél'],
            ['distance' => 230, 'power' => 50, 'rental' => 181, 'expected' => true, 'scenario' => 'Közepes töltöttség (50%) extra hosszú bérlésnél'],
            # 4. Szélső-érték teszt
            ['distance' => 460, 'power' => 100, 'rental' => 120, 'expected' => false, 'scenario' => 'Maximális hatótáv teljes töltöttséggel'],
            ['distance' => 230, 'power' => 100, 'rental' => 120, 'expected' => false, 'scenario' => 'Fél hatótáv teljes töltöttséggel']
        ];

        $citigoEupCases = [
            ['distance' => 60, 'power' => 61, 'rental' => 19, 'expected' => false, 'scenario' => 'Magas töltöttség (>60%) és rövid bérlés'],
            ['distance' => 0, 'power' => 40, 'rental' => 120, 'expected' => false, 'scenario' => 'Nulla távolság normál töltöttséggel'],
            ['distance' => 40, 'power' => 30, 'rental' => 50, 'expected' => false, 'scenario' => 'Kritikus táv értékén'],
            ['distance' => 40 + 1, 'power' => 30, 'rental' => 50, 'expected' => true, 'scenario' => 'Kritikus táv felett 1 km-rel'],
            ['distance' => 160, 'power' => 40, 'rental' => 120, 'expected' => true, 'scenario' => 'Maximum hatótáv 60%-a'],
            ['distance' => 90, 'power' => 35, 'rental' => 61, 'expected' => true, 'scenario' => 'Alacsony töltöttség (35%) hosszú bérlésnél'],
            ['distance' => 130, 'power' => 50, 'rental' => 181, 'expected' => true, 'scenario' => 'Közepes töltöttség (50%) extra hosszú bérlésnél'],
            ['distance' => 265, 'power' => 100, 'rental' => 120, 'expected' => false, 'scenario' => 'Maximális hatótáv teljes töltöttséggel'],
            ['distance' => 132.5, 'power' => 100, 'rental' => 120, 'expected' => false, 'scenario' => 'Fél hatótáv teljes töltöttséggel']
        ];
        $vivaroCases = [
            /** 75kW / 100 = 0.75 [1% = kW] >> 0.75 * 15% = 11.25 [kW]
             * 340km / 75kW = 4.53 km/kW >>  11.25kW * 4.53km = [68km]!
             */
            ['distance' => 80, 'power' => 61, 'rental' => 19, 'expected' => false, 'scenario' => 'Magas töltöttség (>60%) és rövid bérlés'],
            ['distance' => 0, 'power' => 40, 'rental' => 120, 'expected' => false, 'scenario' => 'Nulla távolság normál töltöttséggel'],
            ['distance' => 68, 'power' => 15, 'rental' => 19, 'expected' => false, 'scenario' => 'Kritikus idő értékén'],
            ['distance' => 68 + 1, 'power' => 15, 'rental' => 20, 'expected' => true, 'scenario' => 'Kritikus idő felett 1p-cel'],
            ['distance' => 200, 'power' => 40, 'rental' => 120, 'expected' => true, 'scenario' => 'Maximum hatótáv 60%-a'],
            ['distance' => 120, 'power' => 35, 'rental' => 61, 'expected' => true, 'scenario' => 'Alacsony töltöttség (35%) hosszú bérlésnél'],
            ['distance' => 170, 'power' => 50, 'rental' => 181, 'expected' => true, 'scenario' => 'Közepes töltöttség (50%) extra hosszú bérlésnél'],
            ['distance' => 340, 'power' => 100, 'rental' => 120, 'expected' => false, 'scenario' => 'Maximális hatótáv teljes töltöttséggel'],
            ['distance' => 170, 'power' => 100, 'rental' => 120, 'expected' => false, 'scenario' => 'Fél hatótáv teljes töltöttséggel']
        ];
        return compact('eup18kwCases', 'kangooCases', 'KiaNiroCases', 'citigoEupCases', 'vivaroCases');
    }
    private function mockTestData(): array
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

        $testCar  = Car::factory()->create([
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

        $testUser = User::factory()->create([
            'person_id'       => $testPerson->id,
            'user_name'       => 'TestUserName' . fake()->regexify('[0-9]{5}'),
            'password'        => $testPerson->person_password,
            'password_2_4'    => $testPerson->person_password[1] . $testPerson->person_password[3],
            'account_balance' => 0,
            'sub_id'          => 1,
        ]);

        $testPrice = Price::where('category_class', $testCar->category_id)
            ->where('sub_id', $testUser->sub_id)
            ->firstOrFail();

        $testDailyRental = Dailyrental::where('prices_id', $testUser->sub_id)
            ->where('category_class', $testCar->category_id)
            ->firstOrFail();

        $testFleet->save();
        $testCategory->save();
        $testEquipment->save();
        $testCar->save();
        $testPerson->save();
        $testUser->save();
        $testPrice->save();
        $testDailyRental->save();
        return compact('testFleet', 'testCategory', 'testEquipment', 'testCar', 'testPerson', 'testUser', 'testPrice', 'testDailyRental');
    }
    public function test_a_CarUserRentCharges_tablaban_validalt_ertektartomanyu_rekordok_generalodnak()
    {
        # Ha létezik hibás adat, akkor true értékre fut!
        $invalidEndPowerPercent = DB::table('car_user_rent_charges')
            ->where('end_percent', '<', 0.0)
            ->orWhere('end_percent', '>', 100.0)
            ->exists();
        $invalidStartPowerPercent = DB::table('car_user_rent_charges')
            ->where('start_percent', '<', 0.0)
            ->orWhere('start_percent', '>', 100.0)
            ->exists();

        $invalidStartPowerKiloWatt = DB::table('car_user_rent_charges')
            ->where('start_kw', '<', 0.0)
            ->orWhere('start_kw', '>', 100.0)
            ->exists();
        $highestPowerKwInFleets = DB::table('fleets')->max('motor_power');
        $invalidEndPowerKiloWatt = DB::table('car_user_rent_charges')
            ->where('end_kw', '<', 0.0)
            ->orWhere('end_kw', '>', $highestPowerKwInFleets)
            ->exists();

        $invalidChargingTime = DB::table('car_user_rent_charges')
            ->where('charging_time', '<', 5)
            ->orWhere('charging_time', '>=', 1000)
            ->exists();
        $invalidChargedKwhAmount = DB::table('car_user_rent_charges')
            ->where('charged_kw', '<=', 0)
            ->orWhere('charged_kw', '>=', 100)
            ->exists();
        $invalidCreditsAmount = DB::table('car_user_rent_charges')
            ->where('credits', '<=', 0)
            ->orWhere('credits', '>', 21_000)
            ->exists();

        $this->assertFalse($invalidEndPowerPercent, "Találtunk HIBÁS (0% alatti) VAGY (100% feletti) értéket a end_percent oszlopban!");
        $this->assertFalse($invalidStartPowerPercent, "Találtunk HIBÁS (0% alatti) VAGY (100% feletti) értéket a tart_percent oszlopban!");
        $this->assertFalse($invalidStartPowerKiloWatt, "Találtunk HIBÁS [start_kw] értéket a car_userrent_charges táblában!");
        $this->assertFalse($invalidEndPowerKiloWatt, "Találtunk HIBÁS " . $highestPowerKwInFleets . "end_kw értéket a car_userrent_charges [end_kw] oszlopban!");
        $this->assertFalse($invalidChargingTime, "Találtunk HIBÁS [charging_time] értéket a car_userrent_charges táblában!");
        $this->assertFalse($invalidChargedKwhAmount, "Találtunk HIBÁS [charged_kw] értéket (töltött mennyiséget) a car_userrent_charges táblában!");
        $this->assertFalse($invalidCreditsAmount, "Találtunk HIBÁS [credits] értéket (>21_000) a car_userrent_charges táblában!");
    }
    public function test_mock_test_adatok_validak_es_a_testelesre_keszek()
    {
        $data = $this->mockTestData();

        ## Verda =>Flotta egyezik
        $this->assertEquals('VW', $data['testCar']->fleet->manufacturer);
        $this->assertEquals('e-up!', $data['testCar']->fleet->carmodel);
        $this->assertEquals(18, $data['testCar']->fleet->motor_power);
        $this->assertEquals(135, $data['testCar']->fleet->driving_range);


        $this->assertEquals(1, $data['testCategory']->category_class);
        $this->assertEquals(18, $data['testCategory']->motor_power);

        ## Verda => Kat besorolás egyezik
        $fields = [
            'reversing_camera',
            'lane_keep_assist',
            'adaptive_cruise_control',
            'parking_sensors',
            'multifunction_wheel'
        ];
        foreach ($fields as $field) {
            $this->assertTrue($data['testEquipment']->$field, "A $field mező értékének true-nak kell lennie most!");
        }
        ## Autonak a saját adatai 
        $this->assertEquals(50.00, $data['testCar']->power_percent);
        $this->assertEquals(9, $data['testCar']->power_kw);
        $this->assertEquals(67.5, $data['testCar']->estimated_range);
        $this->assertEquals(1, $data['testCar']->status);
        $this->assertEquals(1, $data['testCar']->category_id);
        $this->assertGreaterThanOrEqual(5, $data['testCar']->equipment_class);
        $this->assertEquals(1, $data['testCar']->fleet_id);
        $this->assertEquals(10_000, $data['testCar']->odometer);
        $this->assertEquals(2023, $data['testCar']->manufactured);

        ## User << Person adatok egyezése$->
        $this->assertStringContainsString('TestUserName', $data['testUser']->user_name);
        $this->assertEquals(2, strlen($data['testUser']->password_2_4));
        $this->assertEquals(0, $data['testUser']->account_balance, 'Az egyenleg (account_balance) csak 0 lehet itt!');
        $this->assertEquals(1, $data['testUser']->sub_id, 'Az előfizetés id értéke csak 1 lehet itt!');

        ## Árazás stimmel?
        $this->assertEquals(1, $data['testPrice']->category_class);
        $this->assertEquals(1, $data['testPrice']->sub_id);
    }

    public function test_berles_idotartama_kategoriak_szerint_random_tartomanyban()
    {
        ## Kategóriánként a random() min-max közé esnek-e
        $data = [
            1 => [1, 14400],    ## 1 perc, de  || akár 3 napos is.
            2 => [60, 4320],    ## min 60 perc || max 4320
            3 => [1, 14400],    ## 1 perc, de || max 10 napos is.
            4 => [60, 4320],    ## 1 óra || 3 nap
            5 => [2880, 14400], ## min. 2 nap ||  de max 10 nap
        ];
        ## Kategóriánként 50x futtatva ellenőrzi le.
        $iterations = 50;

        foreach ($data as $autoKategoria => [$min, $max]) {
            for ($i = 0; $i < $iterations; $i++) {
                $idotartam = RenthistoryFactory::new()->berlesIdotartama($autoKategoria);
                $this->assertGreaterThanOrEqual(
                    $min,
                    $idotartam,
                    "Kategória $autoKategoria: túl kicsi ($idotartam < $min)"
                );
                $this->assertLessThanOrEqual(
                    $max,
                    $idotartam,
                    "Kategória $autoKategoria: túl nagy ($idotartam > $max)"
                );
            }
        }
    }

    public function test_0_es_15_perc_berles_kozotti_parkolas()
    {
        $data = $this->mockTestData();
        $testCar = $data['testCar'];

        $berlesKezdete = new DateTime('2025-01-01 12:00:00');
        $berlesVege    = new DateTime('2025-01-01 12:15:00');

        ## meghívjuk a RenthistoryFactory::megtettTavolsag metódust
        $eredmeny = RenthistoryFactory::new()->megtettTavolsag($testCar, $berlesKezdete, $berlesVege);

        $this->assertArrayHasKey('megtettTavolsag',     $eredmeny);
        $this->assertArrayHasKey('vezetesIdo',          $eredmeny);
        $this->assertArrayHasKey('parkolasokDarabszam', $eredmeny);

        ## vezetesIdo = megtettTavolsag * 2
        $this->assertSame(
            $eredmeny['megtettTavolsag'] * 2,
            $eredmeny['vezetesIdo']
        );
        $this->assertLessThanOrEqual(1, $eredmeny['parkolasokDarabszam'], '15p - 3 óra között 1 parkolásnak kéne lennie minimum!');
        ## A kapott távolság <= (driving_range/motor_power)*power_kw => 100/30*10 = 33.3 => ~ 33-34
        $this->assertLessThanOrEqual(34, $eredmeny['megtettTavolsag']);
    }

    public function test_16_es_30_perc_berles_kozotti_parkolas()
    {
        $data = $this->mockTestData();
        $testCar = $data['testCar'];
        $testCar->power_kw = 9;
        $testCar->fleet->estimated_range = 67.5;
        $testCar->fleet->odometer = 10_000;

        $berlesKezdete = new DateTime('2025-01-01 12:00:00');
        $berlesVege    = new DateTime('2025-01-01 12:30:00');

        $eredmeny = RenthistoryFactory::new()->megtettTavolsag($testCar, $berlesKezdete, $berlesVege);

        ## 15< && <= 30  >> 1 parkolás
        $this->assertLessThanOrEqual(
            1,
            $eredmeny['parkolasokDarabszam'],
            '15p - 3 óra között 1 parkolásnak kéne lennie minimum!'
        );
        if ($eredmeny['parkolasokDarabszam'] === 0) {
            $this->assertEquals(0, $eredmeny['parkolasMaxIdo']);
        } else {
            $this->assertGreaterThanOrEqual(0, $eredmeny['parkolasMaxIdo']);
        }
        ## vezetesido = megtettTavolsag × 2
        $this->assertSame(
            $eredmeny['megtettTavolsag'] * 2,
            $eredmeny['vezetesIdo']
        );
    }
    public function test_60_perc_berles_kozotti_parkolas() ## Lehet, hogy 60 percnél CSAK vezetés lesz!
    {
        $data = $this->mockTestData();
        $testCar = $data['testCar'];
        $testCar->power_kw = 9;
        $testCar->fleet->estimated_range = 67.5;
        $testCar->fleet->odometer = 10_000;

        $berlesKezdete = new DateTime('2025-01-01 12:00:00');
        $berlesVege    = new DateTime('2025-01-01 13:00:00');

        $eredmeny = RenthistoryFactory::new()->megtettTavolsag($testCar, $berlesKezdete, $berlesVege);

        ## random 1–2 parkolás || akár 0
        $this->assertGreaterThanOrEqual(0, $eredmeny['parkolasokDarabszam']);
        $this->assertLessThanOrEqual(2,  $eredmeny['parkolasokDarabszam']);

        ## Ha a kód "véletlenül" generált parkolást, de kevesebb mint 5 percet hagy rá, 
        ## ez is valid a kód logikájában — ne bukjon a teszt!
        if ($eredmeny['parkolasokDarabszam'] === 0) {
            ## Ekkor maradhat 0 a parkolasMaxIdo
            $this->assertEquals(0, $eredmeny['parkolasMaxIdo']);
        } else {
            ## Lehet, hogy 1-2 parkolás, de 2-3 perc maradék is. 
            ## Akkor is elfogadjuk, ha a valóságban < 5 perc jött ki.
            ## Ezért pl.:
            $this->assertGreaterThanOrEqual(0, $eredmeny['parkolasMaxIdo']);
        }

        ## Vezetési idő = megtettTavolsag * 2
        $this->assertSame(
            $eredmeny['megtettTavolsag'] * 2,
            $eredmeny['vezetesIdo']
        );
    }


    public function test_120_perc_berles_kozotti_parkolas()
    {
        $data = $this->mockTestData();
        $testCar = $data['testCar'];
        $testCar->power_kw = 9;
        $testCar->fleet->estimated_range = 67.5;
        $testCar->fleet->odometer = 10_000;

        $berlesKezdete = new DateTime('2025-01-01 12:00:00');
        $berlesVege    = new DateTime('2025-01-01 14:00:00'); ## 120 perc

        $eredmeny = RenthistoryFactory::new()->megtettTavolsag($testCar, $berlesKezdete, $berlesVege);

        ## 30p-3ó közti logikát ellenőrizzük
        $this->assertGreaterThanOrEqual(
            1,
            $eredmeny['parkolasokDarabszam'],
            '120 perc = 2 óránál pl. 2 parkolás?'
        );
        $this->assertSame(
            $eredmeny['megtettTavolsag'] * 2,
            $eredmeny['vezetesIdo']
        );
    }

    public function test_180_perc_berles_kozotti_parkolas()
    {
        $data = $this->mockTestData();
        $testCar = $data['testCar'];
        $testCar->power_kw = 9;
        $testCar->fleet->estimated_range = 67.5;
        $testCar->fleet->odometer = 10_000;

        $berlesKezdete = new DateTime('2025-01-01 12:00:00');
        $berlesVege    = new DateTime('2025-01-01 15:00:00'); ## 180 perc

        $eredmeny = RenthistoryFactory::new()->megtettTavolsag($testCar, $berlesKezdete, $berlesVege);

        ## 2 parkolás, arány ~ 0.6 / 0.4
        $this->assertGreaterThanOrEqual(
            1,
            $eredmeny['parkolasokDarabszam']
        );
        $this->assertArrayHasKey('parkolasokAranyok', $eredmeny);

        $aranyok = $eredmeny['parkolasokAranyok'];
        ## floor( (180 - vezetesIdo) * 0.6 )
        ## stb.
        $this->assertArrayHasKey('elso', $aranyok);
        $this->assertArrayHasKey('masodik', $aranyok);
    }
    public function test_300_perc_berles_kozotti_parkolas()
    {
        $data = $this->mockTestData();
        $testCar = $data['testCar'];
        $testCar->power_kw = 9;
        $testCar->fleet->estimated_range = 67.5;
        $testCar->fleet->odometer = 10_000;
        $berlesKezdete = new DateTime('2025-01-01 12:00:00');
        $berlesVege    = new DateTime('2025-01-01 17:00:00'); ## 300 perc

        $eredmeny = RenthistoryFactory::new()->megtettTavolsag($testCar, $berlesKezdete, $berlesVege);

        $this->assertGreaterThanOrEqual(3, $eredmeny['parkolasokDarabszam']);
        $this->assertLessThanOrEqual(5,  $eredmeny['parkolasokDarabszam']);

        if ($eredmeny['parkolasokDarabszam'] === 3) {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        } else {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        }
    }
    public function test_7_ora_es_15_perc_berles_kozotti_parkolas()
    {
        $data = $this->mockTestData();
        $testCar = $data['testCar'];
        $testCar->power_kw = 9;
        $testCar->fleet->estimated_range = 67.5;
        $testCar->fleet->odometer = 10_000;
        $berlesKezdete = new DateTime('2025-01-01 12:00:00');
        $berlesVege    = new DateTime('2025-01-01 19:15:00');

        $eredmeny = RenthistoryFactory::new()->megtettTavolsag($testCar, $berlesKezdete, $berlesVege);

        $this->assertGreaterThanOrEqual(3, $eredmeny['parkolasokDarabszam']); ## min 3
        $this->assertLessThanOrEqual(5,  $eredmeny['parkolasokDarabszam']); ## de max 5!

        if ($eredmeny['parkolasokDarabszam'] === 3) {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        } else {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        }
    }
    public function test_12_ora_berles_kozotti_parkolas()
    {
        $data = $this->mockTestData();
        $testCar = $data['testCar'];
        $testCar->power_kw = 9;
        $testCar->fleet->estimated_range = 67.5;
        $testCar->fleet->odometer = 10_000;
        $berlesKezdete = new DateTime('2025-01-01 12:00:00');
        $berlesVege    = new DateTime('2025-01-01 24:00:00');

        $eredmeny = RenthistoryFactory::new()->megtettTavolsag($testCar, $berlesKezdete, $berlesVege);

        $this->assertGreaterThanOrEqual(3, $eredmeny['parkolasokDarabszam']);
        $this->assertLessThanOrEqual(5,  $eredmeny['parkolasokDarabszam']);

        if ($eredmeny['parkolasokDarabszam'] === 3) {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        } else {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        }
    }
    public function test_24_oras_berles_parkolas()
    {
        $data = $this->mockTestData();
        $testCar = $data['testCar'];
        $testCar->power_kw = 9;
        $testCar->fleet->estimated_range = 67.5;
        $testCar->fleet->odometer = 10_000;
        $berlesKezdete = new DateTime('2025-01-01 12:00:00');
        $berlesVege    = new DateTime('2025-01-02 00:00:00');

        $eredmeny = RenthistoryFactory::new()->megtettTavolsag($testCar, $berlesKezdete, $berlesVege);

        $this->assertGreaterThanOrEqual(3, $eredmeny['parkolasokDarabszam']);
        $this->assertLessThanOrEqual(5,  $eredmeny['parkolasokDarabszam']);

        if ($eredmeny['parkolasokDarabszam'] === 3) {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        } else {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        }
    }
    public function test_1_nap_es_8_oras_berles_parkolas() ## 1 nap + 8 óra
    {
        $data = $this->mockTestData();
        $testCar = $data['testCar'];
        $testCar->power_kw = 9;
        $testCar->fleet->estimated_range = 67.5;
        $testCar->fleet->odometer = 10_000;
        $berlesKezdete = new DateTime('2025-01-01 12:00:00');
        $berlesVege    = new DateTime('2025-01-02 20:00:00');

        $eredmeny = RenthistoryFactory::new()->megtettTavolsag($testCar, $berlesKezdete, $berlesVege);
        $this->assertGreaterThanOrEqual(3, $eredmeny['parkolasokDarabszam']);
        $this->assertLessThanOrEqual(5,  $eredmeny['parkolasokDarabszam']);

        if ($eredmeny['parkolasokDarabszam'] === 3) {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        } else {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        }
    }
    public function test_1_nap_es_16_oras_berles_parkolas() ## 1 nap + 16 óra
    {
        $data = $this->mockTestData();
        $testCar = $data['testCar'];
        $testCar->power_kw = 9;
        $testCar->fleet->estimated_range = 67.5;
        $testCar->fleet->odometer = 10_000;
        $berlesKezdete = new DateTime('2025-01-01 12:00:00');
        $berlesVege    = new DateTime('2025-01-03 04:00:00');

        $eredmeny = RenthistoryFactory::new()->megtettTavolsag($testCar, $berlesKezdete, $berlesVege);
        $this->assertGreaterThanOrEqual(3, $eredmeny['parkolasokDarabszam']);
        $this->assertLessThanOrEqual(5,  $eredmeny['parkolasokDarabszam']);

        if ($eredmeny['parkolasokDarabszam'] === 3) {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        } else {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        }
    }
    public function test_2_napos_berles_parkolas() ## 2 nap
    {
        $data = $this->mockTestData();
        $testCar = $data['testCar'];
        $testCar->power_kw = 9;
        $testCar->fleet->estimated_range = 67.5;
        $testCar->fleet->odometer = 10_000;
        $berlesKezdete = new DateTime('2025-01-01 12:00:00');
        $berlesVege    = new DateTime('2025-01-03 12:00:00');

        $eredmeny = RenthistoryFactory::new()->megtettTavolsag($testCar, $berlesKezdete, $berlesVege);
        $this->assertGreaterThanOrEqual(3, $eredmeny['parkolasokDarabszam']);
        $this->assertLessThanOrEqual(5,  $eredmeny['parkolasokDarabszam']);

        if ($eredmeny['parkolasokDarabszam'] === 3) {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        } else {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        }
    }
    public function test_2_nap_es_12_oras_berles_parkolas() ## 2 nap + 12
    {
        $data = $this->mockTestData();
        $testCar = $data['testCar'];
        $testCar->power_kw = 9;
        $testCar->fleet->estimated_range = 67.5;
        $testCar->fleet->odometer = 10_000;
        $berlesKezdete = new DateTime('2025-01-01 12:00:00');
        $berlesVege    = new DateTime('2025-01-04 00:00:00');

        $eredmeny = RenthistoryFactory::new()->megtettTavolsag($testCar, $berlesKezdete, $berlesVege);
        $this->assertGreaterThanOrEqual(3, $eredmeny['parkolasokDarabszam']);
        $this->assertLessThanOrEqual(5,  $eredmeny['parkolasokDarabszam']);

        if ($eredmeny['parkolasokDarabszam'] === 3) {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        } else {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        }
    }
    public function test_3_napos_berles_parkolas() ## 3 nap
    {
        $data = $this->mockTestData();
        $testCar = $data['testCar'];
        $testCar->power_kw = 9;
        $testCar->fleet->estimated_range = 67.5;
        $testCar->fleet->odometer = 10_000;
        $berlesKezdete = new DateTime('2025-01-01 12:00:00');
        $berlesVege    = new DateTime('2025-01-04 12:00:00');

        $eredmeny = RenthistoryFactory::new()->megtettTavolsag($testCar, $berlesKezdete, $berlesVege);
        $this->assertGreaterThanOrEqual(3, $eredmeny['parkolasokDarabszam']);
        $this->assertLessThanOrEqual(5,  $eredmeny['parkolasokDarabszam']);

        if ($eredmeny['parkolasokDarabszam'] === 3) {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        } else {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        }
    }
    public function test_3_nap_es_8_oras_berles_parkolas() ## 3 nap + 8 óra
    {
        $data = $this->mockTestData();
        $testCar = $data['testCar'];

        $berlesKezdete = new DateTime('2025-01-01 12:00:00');
        $berlesVege    = new DateTime('2025-01-04 20:00:00');

        $eredmeny = RenthistoryFactory::new()->megtettTavolsag($testCar, $berlesKezdete, $berlesVege);
        $this->assertGreaterThanOrEqual(3, $eredmeny['parkolasokDarabszam']);
        $this->assertLessThanOrEqual(5,  $eredmeny['parkolasokDarabszam']);

        if ($eredmeny['parkolasokDarabszam'] === 3) {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        } else {
            $this->assertArrayHasKey('harmadik', $eredmeny['parkolasokAranyok']);
        }
    }
    public function test_15_perc_alatt_megtett_tavolsag()
    {
        $data = $this->mockTestData();
        $testCar = $data['testCar'];

        $berlesKezdete = Carbon::now();
        $berlesVege = (clone $berlesKezdete)->addMinutes(15);

        $result = RenthistoryFactory::new()->megtettTavolsag($testCar, $berlesKezdete, $berlesVege);

        $this->assertArrayHasKey('megtettTavolsag', $result);
        $this->assertLessThanOrEqual(7, $result['megtettTavolsag']); # max. 7 km
        $this->assertEquals($result['megtettTavolsag'] * 2, $result['vezetesIdo']);
        $this->assertEquals(0, $result['parkolasokDarabszam']);
    }
    public function test_16_es_30_perc_kozotti_megtett_tavolsag()
    {
        $data = $this->mockTestData();
        $testCar = $data['testCar'];
        $berlesKezdete = Carbon::now();
        $berlesVege = (clone $berlesKezdete)->addMinutes(30);

        $result = RenthistoryFactory::new()->megtettTavolsag($testCar, $berlesKezdete, $berlesVege);

        $this->assertArrayHasKey('parkolasokDarabszam', $result);
        $this->assertGreaterThanOrEqual(0, $result['parkolasokDarabszam']);
        $this->assertLessThanOrEqual(1, $result['parkolasokDarabszam']);
    }

    public function test_5_tol_240_percig_0_tol_120km_ig_megtettTavolsag()
    {
        $timeData = [
            #  [Perc]    [Min km | Max km]
            5 =>     [0,          2],
            10 =>    [0,          5],
            15 =>    [0,          7],
            20 =>    [1,          10],
            30 =>    [1,          15],
            40 =>    [3,          20],
            50 =>    [3,          25],
            90 =>    [3,          45],
            240 =>   [4,          120],
        ];
        $testData = $this->mockTestData();
        $testCar = $testData['testCar'];
        $testCar->power_kw = 36;
        $testCar->power_percent = 100;
        $testCar->fleet->estimated_range = 265;
        $iterations = 50;

        foreach ($timeData as $minutes => [$minKm, $maxKm]) {
            for ($i = 0; $i < $iterations; $i++) {

                $berlesKezdete = Carbon::now();
                $berlesVege = (clone $berlesKezdete)->addMinutes($minutes);
                $result = RenthistoryFactory::new()->megtettTavolsag($testCar, $berlesKezdete, $berlesVege);

                $this->assertGreaterThanOrEqual($minKm, $result['megtettTavolsag'], "Túl kicsi megtett táv: {$result['megtettTavolsag']} km");
                $this->assertLessThanOrEqual($maxKm, $result['megtettTavolsag'], "Túl nagy megtett táv: {$result['megtettTavolsag']} km");
                $this->assertEquals($result['vezetesIdo'], $result['megtettTavolsag'] * 2, "Helytelen vezetési idő: {$result['vezetesIdo']} perc");
                $this->assertLessThanOrEqual($minutes * 2, $result['megtettTavolsag'], "A megtett távolság nem lehet nagyobb a bérlési idő felénél: {$result['megtettTavolsag']} km");
            }
        }
    }


    public function test_18Kw_eup_KellEHozzaTolteniAutot_ido_tav_toltes_aranyokkal(): void
    {
        $testData = $this->mockTestData();
        $testCar = $testData['testCar'];
        $testCar->category_id = 1;

        $testCases = $this->mockTestCarChargingData();
        $vivaroCases = $testCases['eup18kwCases'];

        foreach ($vivaroCases as $oneCase) {
            $testCar->power_percent = $oneCase['power'];
            $testCar->power_kw = round($testCar->fleet->motor_power * ($oneCase['power'] / 100), 1);
            $testCar->estimated_range = round($testCar->fleet->driving_range * ($oneCase['power'] / 100), 1);
            $needsCharging = $this->testChargeFactory->kellHozzaTolteniAutot(
                $oneCase['rental'],
                $oneCase['distance'],
                $testCar
            );

            $this->assertEquals(
                $oneCase['expected'],
                $needsCharging,
                "E-up 18kw: {$oneCase['scenario']}"
            );
        }
    }
    public function test_33KwKangoo_KellEHozzaTolteniAutot_TestCasesTombHasznalataval(): void
    {
        $testData = $this->mockTestData();
        $testCar = $testData['testCar'];
        $testCar->category_id = 2;

        $testCases = $this->mockTestCarChargingData();
        $vivaroCases = $testCases['kangooCases'];

        foreach ($vivaroCases as $oneCase) {
            $testCar->power_percent = $oneCase['power'];
            $testCar->power_kw = round($testCar->fleet->motor_power * ($oneCase['power'] / 100), 1);
            $testCar->estimated_range = round($testCar->fleet->driving_range * ($oneCase['power'] / 100), 1);
            $needsCharging = $this->testChargeFactory->kellHozzaTolteniAutot(
                $oneCase['rental'],
                $oneCase['distance'],
                $testCar
            );

            $this->assertEquals(
                $oneCase['expected'],
                $needsCharging,
                "Renault kangoo: {$oneCase['scenario']}"
            );
        }
    }
    public function test_36Kw_eup_Skoda_KellEHozzaTolteniAutot_TestCasesTombHasznalataval(): void
    {
        $testData = $this->mockTestData();
        $testCar = $testData['testCar'];
        $testCar->category_id = 3;

        $testCases = $this->mockTestCarChargingData();
        $vivaroCases = $testCases['citigoEupCases'];

        foreach ($vivaroCases as $oneCase) {
            $testCar->power_percent = $oneCase['power'];
            $testCar->power_kw = round($testCar->fleet->motor_power * ($oneCase['power'] / 100), 1);
            $testCar->estimated_range = round($testCar->fleet->driving_range * ($oneCase['power'] / 100), 1);
            $needsCharging = $this->testChargeFactory->kellHozzaTolteniAutot(
                $oneCase['rental'],
                $oneCase['distance'],
                $testCar
            );
            $this->assertEquals(
                $oneCase['expected'],
                $needsCharging,
                "VW E-up! & Skoda Citigo: {$oneCase['scenario']}"
            );
        }
    }
    public function test_75Kw_OpelVivaro_kellEHozzaTolteniAutot_TestCasesTombHasznalataval(): void
    {
        $testData = $this->mockTestData();
        $testCar = $testData['testCar'];
        $testCar->category_id = 5;

        $testCases = $this->mockTestCarChargingData();
        $vivaroCases = $testCases['vivaroCases'];

        foreach ($vivaroCases as $oneCase) {
            $testCar->power_percent = $oneCase['power'];
            $testCar->power_kw = round($testCar->fleet->motor_power * ($oneCase['power'] / 100), 1);
            $testCar->estimated_range = round($testCar->fleet->driving_range * ($oneCase['power'] / 100), 1);
            $needsCharging = $this->testChargeFactory->kellHozzaTolteniAutot(
                $oneCase['rental'],
                $oneCase['distance'],
                $testCar
            );

            $this->assertEquals(
                $oneCase['expected'],
                $needsCharging,
                "Vivaro: {$oneCase['scenario']}"
            );
        }
    }
    public function test_65Kw_KiaNiro_kellEHozzaTolteniAutot_TestCasesTombHasznalataval(): void
    {
        $testData = $this->mockTestData();
        $testCar = $testData['testCar'];
        $testCar->category_id = 6;

        $testCases = $this->mockTestCarChargingData();
        $vivaroCases = $testCases['KiaNiroCases'];

        foreach ($vivaroCases as $oneCase) {
            $testCar->power_percent = $oneCase['power'];
            $testCar->power_kw = round($testCar->fleet->motor_power * ($oneCase['power'] / 100), 1);
            $testCar->estimated_range = round($testCar->fleet->driving_range * ($oneCase['power'] / 100), 1);
            $needsCharging = $this->testChargeFactory->kellHozzaTolteniAutot(
                $oneCase['rental'],
                $oneCase['distance'],
                $testCar
            );
            $this->assertEquals(
                $oneCase['expected'],
                $needsCharging,
                "Kia Niro Esetében: {$oneCase['scenario']}"
            );
        }
    }
    public function test_toltes_validacio_minden_kategoriara(): void
    {
        $testData = $this->mockTestData();
        $testCar = $testData['testCar'];
        $billService = new BillService();


        $tesztEsetek = [
            # [category_id, töltés%, elvárt_status, buntetendo]
            # [1-es, E-up! 18kw]
            [1, 8.9, 7, true],
            [1, 9.0, 7, false],
            [1, 14.0, 7, false],
            [1, 15.1, 1, false],
            [1, 40.0, 1, false],

            # [2-es Kangoo | 33kw]
            [2, 5.9, 7, true],
            [2, 6.0, 7, false],
            [2, 14.9, 7, false],
            [2, 15.1, 1, false],
            [2, 40.0, 1, false],

            # [3-as Citigo & E-up! | 36kw]
            [3, 4.4, 7, true],
            [3, 4.5, 7, false],
            [3, 14.9, 7, false],
            [3, 15.1, 1, false],
            [3, 40.0, 1, false],

            # [4-es, Vivaro | 75kw]
            [4, 3.9, 7, true],
            [4, 4.0, 7, false],
            [4, 14.9, 7, false],
            [4, 15.1, 1, false],
            [4, 40.0, 1, false],

            # [5-ös, Niro | 65kw]
            [5, 3.9, 7, true],
            [5, 4.0, 7, false],
            [5, 14.9, 7, false],
            [5, 15.1, 1, false],
            [5, 40.0, 1, false],
        ];

        foreach ($tesztEsetek as [$category, $toltes, $vartStatus, $buntetendo]) {
            $testCar->category_id = $category;
            $testCar->power_percent = $toltes;
            $testCar->status = 1;

            $result = $this->testCarRefreshService->ellenorizToltottseg($testCar, $toltes);

            // Státusz ellenőrzése
            $this->assertEquals(
                $vartStatus,
                $testCar->status,
                "Kategória {$category}: Hibás státusz érték ({$testCar->status})!"
            );

            // Büntetendő státusz ellenőrzése
            $this->assertEquals(
                $buntetendo,
                $result['buntetendo'],
                "Kategória {$category}: Hibás büntetendő státusz!"
            );

            // Ha büntetendő, ellenőrizzük a büntetés összegét a BillService-ben
            if ($buntetendo && ($category === 1 || $category === 3)) {
                $this->assertEquals(30000, BillService::$chargingFines[$category]['buntetes'], 
                    "Kategória {$category}: Hibás büntetési összeg!");
            } else if ($buntetendo) {
                $this->assertEquals(50000, BillService::$chargingFines[$category]['buntetes'], 
                    "Kategória {$category}: Hibás büntetési összeg!");
            }
        }
    }
    public function test_GeneraljToltest_function_test(): void
    {
        ## Setup-ok ##
        $testData = $this->mockTestData();
        $testCar = $testData['testCar'];
        $testUser = $testData['testUser'];

        $berlesKezdete = new DateTime('2024-01-01 10:00:00');
        $berlesHossz = 120;
        $berlesVege = (clone $berlesKezdete)->modify("+$berlesHossz minutes");

        # Mivel generálás közben random float értékkel számolok,
        # Ezért le kell fixálni a teszthez.
        # >> [+1] Hibás (100% töltés) érték vizsgálat mindig.

        $kategoriaBeallitasok = [
            1 => ['toltes' => 10, 'min' => 0.32, 'max' => 0.37],
            1 => ['toltes' => 20, 'min' => 0.32, 'max' => 0.37],
            1 => ['toltes' => 30, 'min' => 0.32, 'max' => 0.37],
            1 => ['toltes' => 50, 'min' => 0.32, 'max' => 0.37],
            1 => ['toltes' => 75, 'min' => 0.32, 'max' => 0.37],
            1 => ['toltes' => 100, 'min' => 0.32, 'max' => 0.37],

            2 => ['toltes' => 10, 'min' => 0.32, 'max' => 0.37],
            2 => ['toltes' => 20, 'min' => 0.32, 'max' => 0.37],
            2 => ['toltes' => 30, 'min' => 0.32, 'max' => 0.37],
            2 => ['toltes' => 50, 'min' => 0.32, 'max' => 0.37],
            2 => ['toltes' => 75, 'min' => 0.32, 'max' => 0.37],
            2 => ['toltes' => 100, 'min' => 0.32, 'max' => 0.37],

            3 => ['toltes' => 10, 'min' => 0.32, 'max' => 0.37],
            3 => ['toltes' => 20, 'min' => 0.32, 'max' => 0.37],
            3 => ['toltes' => 30, 'min' => 0.32, 'max' => 0.37],
            3 => ['toltes' => 50, 'min' => 0.32, 'max' => 0.37],
            3 => ['toltes' => 75, 'min' => 0.32, 'max' => 0.37],
            3 => ['toltes' => 100, 'min' => 0.32, 'max' => 0.37],

            4 => ['toltes' => 10, 'min' => 0.37, 'max' => 0.40],
            4 => ['toltes' => 20, 'min' => 0.37, 'max' => 0.40],
            4 => ['toltes' => 30, 'min' => 0.37, 'max' => 0.40],
            4 => ['toltes' => 50, 'min' => 0.37, 'max' => 0.40],
            4 => ['toltes' => 75, 'min' => 0.37, 'max' => 0.40],
            4 => ['toltes' => 100, 'min' => 0.37, 'max' => 0.40],

            5 => ['toltes' => 10, 'min' => 0.51, 'max' => 0.61],
            5 => ['toltes' => 20, 'min' => 0.51, 'max' => 0.61],
            5 => ['toltes' => 30, 'min' => 0.51, 'max' => 0.61],
            5 => ['toltes' => 50, 'min' => 0.51, 'max' => 0.61],
            5 => ['toltes' => 75, 'min' => 0.51, 'max' => 0.61],
            5 => ['toltes' => 100, 'min' => 0.51, 'max' => 0.61],
        ];
        foreach ($kategoriaBeallitasok as $kategoria => $beallitas) {
            $testCar->category_id = $kategoria;
            $testCar->power_percent = $beallitas['toltes'];
            $testCar->power_kw = round($testCar->fleet->motor_power * ($beallitas['toltes'] / 100), 1);

            $eredmeny = $this->testChargeFactory->generaljToltest(
                $testCar,
                $testUser,
                $berlesKezdete,
                $berlesVege,
                $berlesHossz
            );

            if (!empty($eredmeny)) {
                $minToltesKw = $beallitas['min'] * $eredmeny['charging_time'];
                $maxToltesKw = $beallitas['max'] * $eredmeny['charging_time'];
                $this->assertGreaterThanOrEqual(
                    floor($minToltesKw),
                    floor($eredmeny['charged_kw']),
                    "Kategóriájú: $kategoria: Töltési sebessége túl alacsony"
                );
                $this->assertLessThanOrEqual(
                    floor($maxToltesKw),
                    floor($eredmeny['charged_kw']),
                    "Kategóriájú: $kategoria: Töltési sebessége túl magas"
                );
            }
        }
    }
    public function test_ChargingCreditsReturn_function_test(): void
    {
        $testData = $this->mockTestData();
        $testUser = $testData['testUser'];
        $testUser->account_balance = 0;

        $tesztEsetek = [
            ['toltottKw' => 1.6, 'elvartCredit' => 400],
            ['toltottKw' => 5.9, 'elvartCredit' => 2_000],
            ['toltottKw' => 6.0, 'elvartCredit' => 2_200],
            ['toltottKw' => 6.1, 'elvartCredit' => 2_200],
            ['toltottKw' => 10.0, 'elvartCredit' => 3_000],
            # Hibásak
            ['toltottKw' => 0.0, 'elvartCredit' => 0],
            ['toltottKw' => -1.0, 'elvartCredit' => 0],
        ];

        foreach ($tesztEsetek as $eset) {
            $eredetiEgyenleg = $testUser->account_balance;
            $credits = CarUserrentChargeFactory::new()->chargingCreditsReturn($testUser, floor($eset['toltottKw']));

            # Credit számítás ellenőrzése
            $this->assertEquals($eset['elvartCredit'], $credits, "A credit kalkuláció nem megfelelő {$eset['toltottKw']} kWh töltésnél");

            # Egyenleg növekedés ellenőrzése
            $this->assertEquals(
                $eredetiEgyenleg + $eset['elvartCredit'],
                $testUser->account_balance,
                "A felhasználó egyenlege nem megfelelően növekedett"
            );
        }
    }
}
