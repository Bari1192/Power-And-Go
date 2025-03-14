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

class BonusMinutes_DailyRentalChargeCampaign_Test extends TestCase
{
    use DatabaseTransactions;

    private Car $testCar;
    private User $testUser;
    private Price $testPrice;
    private DateTime $startDate;
    private DateTime $endDate;
    private Dailyrental $testDailyRental;
    protected BonusMinutesService $bonusMinutesService;
    protected DailyRentalBonusRule $dailyRentalBonusRule;
    protected BonusRuleService $bonusRuleService;
    protected PlantTreeCampaignRule $plantTreeCampaignRule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bonusMinutesService = new BonusMinutesService();
        $this->dailyRentalBonusRule = new DailyRentalBonusRule();
        $this->plantTreeCampaignRule = new PlantTreeCampaignRule();
        $this->bonusRuleService = new BonusRuleService($this->bonusMinutesService, $this->dailyRentalBonusRule);
        $this->bonusRuleService->registerRule($this->dailyRentalBonusRule);
        $this->setupTestData();
    }

    private function setupTestData(): void
    {
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
        $this->bonusRuleService->registerRule(new PlantTreeCampaignRule(), new DailyRentalBonusRule());
    }
    public function testUserIsEligibleForDailyRentalChargeBonusMinutesCampaign()
    {
        // return $isDailyRental && $endPercent >= 70.0;
        $context = [
            'is_daily_rental' => true,
            'end_percent' => 70,
            'driving_minutes' => 200,
            'rental_duration' => 200,
        ];
        $this->assertTrue($this->dailyRentalBonusRule->isEligible($this->testUser, $context));
    }
    public function testUserIsNotEligibleForDailyRentalChargeBonusMinutesCampaign()
    {
        // return $isDailyRental && $endPercent >= 70.0;
        $context = [
            'is_daily_rental' => true,
            'end_percent' => 69,
            'driving_minutes' => 200,
            'rental_duration' => 200,
        ];
        $this->assertFalse($this->dailyRentalBonusRule->isEligible($this->testUser, $context));
    }
    public function testTransactionNumberShouldBe_2_InLongTermRentingWithPlantTreeCampaignAndDailyRentalCampaign()
    {
        $this->testUser->plant_tree = true;
        $this->testUser->driving_minutes = 100;
        $this->testUser->save();
    
        $context = [
            'is_daily_rental' => true,
            'end_percent' => 75,
            'driving_minutes' => 200,
            'rental_duration' => 200,
        ];
    
        $this->bonusMinutesService->addDrivingMinutes($this->testUser, 150);
        
        $tranzakciok = BonusMinutesTransaction::where('user_id', $this->testUser->id)->get();
        $this->assertEquals(1, $tranzakciok->count());
        $this->assertEquals('plant_tree', $tranzakciok[0]->source);
        $this->assertEquals('debit', $tranzakciok[0]->type);
        $this->assertEquals(20, $tranzakciok[0]->amount);
        
        if ($this->dailyRentalBonusRule->isEligible($this->testUser, $context)) {
            $bonusMinutes = $this->dailyRentalBonusRule->calculateBonusMinutes($this->testUser, $context);
            $this->bonusMinutesService->addBonusMinutes(
                $this->testUser,
                $bonusMinutes,
                $this->dailyRentalBonusRule->getSource(),
                $this->dailyRentalBonusRule->getType(),
                $this->dailyRentalBonusRule->getReason($this->testUser, $context)
            );
        }

        $tranzakciok = BonusMinutesTransaction::where('user_id', $this->testUser->id)->get();
        $this->assertEquals(2, $tranzakciok->count());
        $this->assertEquals('system', $tranzakciok[1]->source);
        $this->assertEquals('debit', $tranzakciok[1]->type);
        $this->assertEquals(45, $tranzakciok[1]->amount);
    }
}
