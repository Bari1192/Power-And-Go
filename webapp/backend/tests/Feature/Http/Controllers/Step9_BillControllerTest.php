<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bill;
use App\Models\Car;
use App\Models\User;
use App\Policies\BillService;
use App\Policies\CarRefreshService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class Step9_BillControllerTest extends TestCase
{
    use DatabaseTransactions;
    public $fixedDateTime;
    private CarRefreshService $testCarRefreshService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->testCarRefreshService = new CarRefreshService();
        $this->fixedDateTime = now()->format('Y-m-d H:i:s');
    }
    private function setupTestBill()
    {
        $rental = DB::table('car_user_rents')
            ->where('rentstatus', 2)
            ->first();

        if (!$rental) {
            $this->markTestSkipped('No completed rental found');
        }
        $car = Car::find($rental->car_id);
        $user = User::find($rental->user_id);

        return [
            'rental' => $rental,
            'car' => $car,
            'user' => $user
        ];
    }
    public function test_can_get_all_bills_data()
    {
        $response = $this->get('/api/bills');
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertNotEmpty($data, 'Az adatbázisban nincsenek elérhető számlák.');
    }
    public function test_can_get_random_bills_data_with_using_array_rand()
    {
        $response = $this->get('/api/bills');
        $response->assertStatus(200);
        $data = $response->json('data');

        $oneBill = fake()->randomElement($data);

        $response = $this->assertArrayHasKey('id', $oneBill, 'Az `id` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('user_id', $oneBill, 'Az `user_id` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('username', $oneBill, 'Az `username` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('person_id', $oneBill, 'Az `person_id` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('person', $oneBill, 'Az `person` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('car_id', $oneBill, 'Az `car_id` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('rent_start', $oneBill, 'Az `rent_start` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('rent_close', $oneBill, 'Az `rent_close` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('distance', $oneBill, 'Az `distance` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('parking_minutes', $oneBill, 'Az `parking_minutes` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('driving_minutes', $oneBill, 'Az `driving_minutes` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('total_cost', $oneBill, 'Az `total_cost` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('bill_type', $oneBill, 'Az `bill_type` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('invoice_date', $oneBill, 'Az `invoice_date` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('invoice_status', $oneBill, 'Az `invoice_status` érték nem sikerült lekérni.');
    }
    public function test_can_create_charging_penalty_bill()
    {
        $car = Car::first();
        $car->status = 7;
        $car->power_percent = 2.0;
        $car->save();

        $rental = DB::table('car_user_rents')
            ->where('car_id', $car->id)
            ->where('rentstatus', 2)
            ->first();

        if (!$rental) {
            $this->markTestSkipped('Nincs találat!');
        }

        $user = User::find($rental->user_id);
        $billService = new BillService(app(CarRefreshService::class));
        $billService->createChargingFine($car, $user, $rental);

        $this->assertDatabaseHas('bills', [
            'bill_type' => 'charging_penalty',
            'user_id' => $user->id,
            'car_id' => $car->id
        ]);
    }
    public function test_can_get_random_bills_data_with_using_api()
    {
        $response = $this->get('/api/bills');
        $response->assertStatus(200);
        $data = $response->json('data');

        $lenght = count($data);
        $randomNumber = random_int(1, $lenght);

        $response = $this->get("/api/bills/{$randomNumber}");
        $oneBill = $response->json('data');

        $expectedDataKeys = [
            "id",
            "user_id",
            "username",
            "person_id",
            "person",
            "car_id",
            "rent_start",
            "rent_close",
            "distance",
            "parking_minutes",
            "driving_minutes",
            "total_cost",
            "bill_type",
            "invoice_date",
            "invoice_status",
        ];
        foreach ($expectedDataKeys as $key) {
            $this->assertArrayHasKey($key, $oneBill, "Hiányzik a kulcsmező: {$key}");
        }
    }
    public function test_cannot_create_employee_without_existing_user_id()
    {
        $billData = [
            "bill_type" => "rental",
            "user_id" => null,
            "person_id" => 237,
            "car_id" => 1,
            "total_cost" => 5000,
            "distance" => 10,
            "parking_minutes" => 0,
            "driving_minutes" => 14,
            "rent_start" => $this->fixedDateTime,
            "rent_close" => $this->fixedDateTime,
            "invoice_date" => $this->fixedDateTime,
            "invoice_status" => "pending"
        ];
        $response = $this->postJson('/api/bills', $billData);
        $response->assertStatus(422);
    }
    public function test_cannot_create_employee_without_existing_person_id()
    {
        $billData = [
            "bill_type" => "rental",
            "user_id" => 46,
            "person_id" => null,
            "car_id" => 1,
            "total_cost" => 5000,
            "distance" => 10,
            "parking_minutes" => 0,
            "driving_minutes" => 14,
            "rent_start" => $this->fixedDateTime,
            "rent_close" => $this->fixedDateTime,
            "invoice_date" => $this->fixedDateTime,
            "invoice_status" => "pending"
        ];
        $response = $this->postJson('/api/bills', $billData);
        $response->assertStatus(422);
    }

    public function test_cannot_create_employee_without_existing_car_id()
    {
        $billData = [
            "bill_type" => "rental",
            "user_id" => 46,
            "person_id" => 237,
            "car_id" => null,
            "total_cost" => 5000,
            "distance" => 10,
            "parking_minutes" => 0,
            "driving_minutes" => 14,
            "rent_start" => $this->fixedDateTime,
            "rent_close" => $this->fixedDateTime,
            "invoice_date" => $this->fixedDateTime,
            "invoice_status" => "pending"
        ];
        $response = $this->postJson('/api/bills', $billData);
        $response->assertStatus(422);
    }
    public function test_can_get_username_and_person_full_name_without_posting_them_directly()
    {
        $data = $this->setupTestBill();

        $billService = new BillService($this->testCarRefreshService);
        $billService->createRentBill($data['car'], $data['user'], $data['rental']);

        $latestBill = Bill::latest('id')->first();
        $response = $this->get("/api/bills/{$latestBill->id}");
        $responseData = $response->json('data');

        $this->assertArrayHasKey('username', $responseData, "Nem kaptuk meg a `username` kulcsot.");
        $this->assertNotNull($responseData['username'], "A `username` kulcs értéke üresen jött vissza.");
        $this->assertArrayHasKey('person', $responseData, "Nem kaptuk meg a `person` kulcsot.");
        $this->assertNotNull($responseData['person'], "A `person` kulcs értéke üresen jött vissza.");
    }
    public function test_can_create_new_bill_into_database()
    {
        $rental = DB::table('car_user_rents')
            ->where('rentstatus', 2)
            ->first();

        if (!$rental) {
            $this->markTestSkipped('No completed rental found');
        }

        $car = Car::find($rental->car_id);
        $user = User::find($rental->user_id);

        $billService = new BillService($this->testCarRefreshService);
        $billService->createRentBill($car, $user, $rental);

        $this->assertDatabaseHas('bills', [
            'bill_type' => 'rental',
            'user_id' => $user->id,
            'car_id' => $car->id,
            'rent_id' => $rental->id
        ]);
    }
    public function test_can_modify_random_bill_data_in_database()
    {
        $data = $this->setupTestBill();

        $billService = new BillService($this->testCarRefreshService);
        $billService->createRentBill($data['car'], $data['user'], $data['rental']);

        $latestBill = Bill::latest('id')->first();

        $modifiedData = [
            "id" => $latestBill->id,
            "bill_type" => "rental",
            "user_id" => $data['user']->id,
            "person_id" => $data['user']->person_id,
            "car_id" => $data['car']->id,
            "rent_id" => $data['rental']->id,
            "total_cost" => 8888,
            "distance" => 88,
            "parking_minutes" => 66,
            "driving_minutes" => 666,
            "rent_start" => $this->fixedDateTime,
            "rent_close" => $this->fixedDateTime,
            "invoice_date" => $this->fixedDateTime,
            "invoice_status" => "pending",
            "email_sent" => 1,
        ];

        $this->putJson("/api/bills/{$latestBill->id}", $modifiedData)
            ->assertStatus(200);

        unset($modifiedData['id']);
        $this->assertDatabaseHas('bills', $modifiedData);
    }
    public function test_can_delete_random_bill_from_database_bills_table()
    {
        $response = $this->get("/api/bills");
        $response->assertStatus(200);
        $data = $response->json('data');

        $lenght = count($data);
        $randomNumber = random_int(1, $lenght);
        $response = $this->get("/api/bills/{$randomNumber}");
        $response->assertStatus(200);
        $bill = $response->json('data');

        $response = $this->delete("/api/bills/{$randomNumber}");
        $response->assertStatus(204);

        $response = $this->assertDatabaseMissing('bills', [
            "id" => $bill['id'],
            "bill_type" => $bill["bill_type"],
            "user_id" => $bill['user_id'],
            "person_id" => $bill['person_id'],
            "car_id" => $bill['car_id'],
            "total_cost" => $bill["total_cost"],
            "distance" => $bill["distance"],
            "parking_minutes" => $bill["parking_minutes"],
            "driving_minutes" => $bill["driving_minutes"],
            "rent_start" => $bill["rent_start"],
            "rent_close" => $bill["rent_close"],
            "invoice_date" => $bill["invoice_date"],
            "invoice_status" => $bill["invoice_status"],
        ]);
    }
}
