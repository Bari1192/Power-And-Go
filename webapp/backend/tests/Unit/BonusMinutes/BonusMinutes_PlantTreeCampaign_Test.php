<?php

namespace Tests\Unit\BonusMinutes;

use App\Models\BonusMinutesTransaction;
use App\Models\Car;
use App\Models\Category;
use App\Models\Dailyrental;
use App\Models\Equipment;
use App\Models\Fleet;
use App\Models\Person;
use App\Models\Price;
use App\Models\User;
use App\Rules\DailyRentalBonusRule;
use App\Rules\PlantTreeCampaignRule;
use App\Services\BonusMinutesService;
use App\Services\BonusRuleService;
use DateTime;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BonusMinutes_PlantTreeCampaign_Test extends TestCase
{

    use DatabaseTransactions;
    private Car $testCar;
    private User $testUser;
    private Price $testPrice;
    private DateTime $startDate;
    private DateTime $endDate;
    private Dailyrental $testDailyRental;
    protected BonusMinutesService $bonusMinutesService;
    protected BonusRuleService $bonusRuleService;
    protected PlantTreeCampaignRule $plantTreeCampaignRule;
    protected DailyRentalBonusRule $dailyRentalBonusRule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bonusMinutesService = new BonusMinutesService();

        // Először létrehozzuk a szabály objektumokat
        $this->dailyRentalBonusRule = new DailyRentalBonusRule();
        $this->plantTreeCampaignRule = new PlantTreeCampaignRule();
        $this->bonusRuleService = new BonusRuleService($this->bonusMinutesService, $this->dailyRentalBonusRule);
        $this->setupTestData();
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

        $pin = fake()->regexify('[0-9]{4}');
        $this->testUser = User::factory()->create([
            'person_id'       => $testPerson->id,
            'user_name'       => 'TestUserName' . fake()->regexify('[0-9]{5}'),
            'pin'             => $pin,
            'password_2_4'    => $pin[1] . $pin[3],
            'account_balance' => 0,
            'sub_id'          => 1,
            'driving_minutes' => 200,
            'bonus_minutes' => 0,
            'bonus_min_exp' => "2025-04-01",
            'plant_tree' => true

        ]);
        $this->testPrice = Price::where('category_class', $this->testCar->category_id)
            ->where('sub_id', $this->testUser->sub_id)
            ->firstOrFail();

        $this->testDailyRental = Dailyrental::where('prices_id', $this->testUser->sub_id)
            ->where('category_class', $this->testCar->category_id)
            ->firstOrFail();

        $this->startDate = new DateTime('2025-01-01 10:00:00');
        $this->endDate = new DateTime('2025-01-01 12:00:00');

        $this->bonusRuleService->registerRule(new PlantTreeCampaignRule());
    }
    public function testUserIsNotEligibleForPlantTreeCampaign()
    {
        $this->testUser->plant_tree = false;
        $this->assertFalse($this->plantTreeCampaignRule->isEligible($this->testUser, []));
    }

    public function testUserIsAllowedForPlantTreeCampaign()
    {
        $this->assertTrue($this->plantTreeCampaignRule->isEligible($this->testUser, []));
    }

    public function testBonusMinutesFunctionWith_20MinutesDriving()
    {
        $this->bonusMinutesService->addDrivingMinutes($this->testUser, 20);
        $this->testUser->refresh();
        $this->assertEquals(0, $this->testUser->bonus_minutes);
        $this->assertEquals(180, $this->testUser->driving_minutes);
    }
    public function testBonusMinutesFunctionWith_40MinutesDriving()
    {
        $this->bonusMinutesService->addDrivingMinutes($this->testUser, 40);
        $this->testUser->refresh();
        $this->assertEquals(0, $this->testUser->bonus_minutes);
        $this->assertEquals(160, $this->testUser->driving_minutes);
    }
    public function testBonusMinutesFunctionWith_200MinutesDriving()
    { ## 200 perc vezetés >> 20 bónusz percet kap >> 200 perc hátravan újra
        $this->bonusMinutesService->addDrivingMinutes($this->testUser, 200);
        $this->testUser->refresh();
        $this->assertEquals(20, $this->testUser->bonus_minutes);
        $this->assertEquals(200, $this->testUser->driving_minutes);
    }
    public function testBonusMinutesFunctionWith_100MinutesDrivingAndUserHas_50MinutesBonus()
    { ## ## 50 perc a bónuszig >> 100 perc vezetés >> 20 bónusz percet kap >> 200 perc hátravan újra

        $this->testUser->driving_minutes = 50;

        $this->bonusMinutesService->addDrivingMinutes($this->testUser, 100);
        $this->testUser->refresh();

        $this->assertEquals(20, $this->testUser->bonus_minutes);
        $this->assertEquals(150, $this->testUser->driving_minutes);
    }
    public function testBonusMinutesFunctionCanNotAddMoreThan_200MinutesBonusInOneRent()
    { ## User 250 percet vezet >> 200 percnél többet nem ír jóvá a rendszer
        $this->testUser->driving_minutes = 200;
        $this->bonusMinutesService->addDrivingMinutes($this->testUser, 250);
        $this->testUser->refresh();

        $this->assertNotEquals(150, $this->testUser->driving_minutes);
        $this->assertNotEquals(40, $this->testUser->bonus_minutes);
    }
    public function testBonusMinutesFunctionCanNotAddDoubleBonusMinutesAfter_400MinutesDriving()
    { ## User 400 percet vezet >> 200 perc után jár 20 bónusz perc
        ## Egy bérlés során a 200 percért járó 20 bón. többet nem írhat jóvá a rendszer!

        $this->testUser->driving_minutes = 200;
        $this->bonusMinutesService->addDrivingMinutes($this->testUser, 400);
        $this->testUser->refresh();
        $this->assertNotEquals(40, $this->testUser->bonus_minutes);
    }
    public function testTransactionNumberShouldBe_1_InShortTermRentingAndDedicatedPlantTreeCampaign()
    {
        $this->testUser->driving_minutes = 100;
        $this->bonusMinutesService->addDrivingMinutes($this->testUser, 150);

        $tranzakciok = BonusMinutesTransaction::where('user_id', $this->testUser->id)->get();
        $this->assertEquals(1, $tranzakciok->count());
        $this->assertEquals('plant_tree', $tranzakciok[0]->source);
        $this->assertEquals('debit', $tranzakciok[0]->type);
        $this->assertEquals(20, $tranzakciok[0]->amount);
    }
}
