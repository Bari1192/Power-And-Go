<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Bill;
use Tests\TestCase;

class Step9_BillControllerTest extends TestCase
{
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
        $response = $this->assertArrayHasKey('rent_start_date', $oneBill, 'Az `rent_start_date` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('rent_start_time', $oneBill, 'Az `rent_start_time` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('rent_end_date', $oneBill, 'Az `rent_end_date` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('rent_end_time', $oneBill, 'Az `rent_end_time` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('driving_distance', $oneBill, 'Az `driving_distance` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('parking_minutes', $oneBill, 'Az `parking_minutes` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('driving_minutes', $oneBill, 'Az `driving_minutes` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('total_cost', $oneBill, 'Az `total_cost` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('bill_type', $oneBill, 'Az `bill_type` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('invoice_date', $oneBill, 'Az `invoice_date` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('invoice_status', $oneBill, 'Az `invoice_status` érték nem sikerült lekérni.');
    }
    public function test_can_create_charging_penalty_bill()
    {
        $response = $this->get('/api/cars');
        $response->assertStatus(200);
        $data = $response->json('data');

        $length = count($data);
        $randNumber = fake()->numberBetween(1, $length);
        $response = $this->get("/api/cars/{$randNumber}");
        $response->assertStatus(200);

        $response = $this->get("/api/users");
        $response->assertStatus(200);
        $userData = $response->json('data');

        $first = $userData[0] ?? null;
        $this->assertNotNull($first, 'The person data array is empty.');
        $this->assertArrayHasKey('user_id', $first, 'The `user_id` key is missing from the first person data.');
        $this->assertArrayHasKey('person_id', $first, 'The `person_id` key is missing from the first person data.');

        $createChargingBill = [
            "bill_type" => "charging_penalty",
            "user_id" => $first['user_id'],
            "person_id" => $first['person_id'],
            "car_id" => $randNumber,
            "total_cost" => 30000,
            "driving_distance" => 10,
            "parking_minutes" => 10,
            "driving_minutes" => 10,
            "rent_start_date" => now()->format('Y-m-d'),
            "rent_start_time" => now()->format('H:i:s'),
            "rent_end_date" => now()->format('Y-m-d'),
            "rent_end_time" => now()->format('H:i:s'),
            "invoice_date" => now()->format('Y-m-d H:i:s'),
            "invoice_status" => "pending",
        ];

        $response = $this->postJson('/api/bills', $createChargingBill);
        $response->assertStatus(201);

        $latestBill = Bill::latest('id')->first();

        // Díjak ellenőrzése
        $response = $this->get("/api/bills/{$randNumber}/fees");
        $response->assertStatus(200);
        $feesData = $response->json('data');

        $this->assertNotEmpty($feesData, 'Fees data cannot be empty.');

        // Adatbázis ellenőrzése
        $this->assertDatabaseHas('bills', [
            "id" => $latestBill->id,
            "bill_type" => "charging_penalty",
            "user_id" => $first['user_id'],
            "person_id" => $first['person_id'],
            "car_id" => $randNumber,
            "total_cost" => 30000,
            "driving_distance" => 10,
            "parking_minutes" => 10,
            "driving_minutes" => 10,
            "rent_start_date" => now()->format('Y-m-d'),
            "rent_start_time" => now()->format('H:i:s'),
            "rent_end_date" => now()->format('Y-m-d'),
            "rent_end_time" => now()->format('H:i:s'),
            "invoice_date" => now()->format('Y-m-d H:i:s'),
            "invoice_status" => "pending",
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
            "rent_start_date",
            "rent_start_time",
            "rent_end_date",
            "rent_end_time",
            "driving_distance",
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
            "driving_distance" => 10,
            "parking_minutes" => 0,
            "driving_minutes" => 14,
            "rent_start_date" => "2024-07-30",
            "rent_start_time" => "01:44:55",
            "rent_end_date" => "2024-07-30",
            "rent_end_time" => "01:58:49",
            "invoice_date" => "2025-01-19 16:34:49",
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
            "driving_distance" => 10,
            "parking_minutes" => 0,
            "driving_minutes" => 14,
            "rent_start_date" => "2024-07-30",
            "rent_start_time" => "01:44:55",
            "rent_end_date" => "2024-07-30",
            "rent_end_time" => "01:58:49",
            "invoice_date" => "2025-01-19 16:34:49",
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
            "driving_distance" => 10,
            "parking_minutes" => 0,
            "driving_minutes" => 14,
            "rent_start_date" => "2024-07-30",
            "rent_start_time" => "01:44:55",
            "rent_end_date" => "2024-07-30",
            "rent_end_time" => "01:58:49",
            "invoice_date" => "2025-01-19 16:34:49",
            "invoice_status" => "pending"
        ];
        $response = $this->postJson('/api/bills', $billData);
        $response->assertStatus(422);
    }
    public function test_can_get_username_and_person_full_name_without_posting_them_directly()
    {
        $billData = [
            "bill_type" => "rental",
            "user_id" => 46,
            "person_id" => 237,
            "car_id" => 1,
            "total_cost" => 5000,
            "driving_distance" => 10,
            "parking_minutes" => 0,
            "driving_minutes" => 14,
            "rent_start_date" => "2024-07-30",
            "rent_start_time" => "01:44:55",
            "rent_end_date" => "2024-07-30",
            "rent_end_time" => "01:58:49",
            "invoice_date" => "2025-01-19 16:34:49",
            "invoice_status" => "pending"
        ];
        $response = $this->postJson('/api/bills', $billData);
        $response->assertStatus(201);

        $latestData = Bill::latest('id')->first();
        $response = $this->get("/api/bills/{$latestData->id}");
        $data = $response->json('data');

        $this->assertArrayHasKey('username', $data, "Nem kaptuk meg a `username` kulcsot.");
        $this->assertNotNull($data['username'], "A `username` kulcs értéke üresen jött vissza.");

        $this->assertArrayHasKey('person', $data, "Nem kaptuk meg a `person` kulcsot.");
        $this->assertNotNull($data['person'], "A `person` kulcs értéke üresen jött vissza.");
    }
    public function test_can_create_new_bill_into_database()
    {
        $billData = [
            "bill_type" => "rental",
            "user_id" => 46,
            "person_id" => 237,
            "car_id" => 1,
            "total_cost" => 666,
            "driving_distance" => 66,
            "parking_minutes" => 6,
            "driving_minutes" => 666,
            "rent_start_date" => "2024-07-30",
            "rent_start_time" => "01:44:55",
            "rent_end_date" => "2024-07-30",
            "rent_end_time" => "01:58:49",
            "invoice_date" => "2025-01-19 16:34:49",
            "invoice_status" => "pending"
        ];
        $response = $this->postJson('/api/bills', $billData);
        $response->assertStatus(201);

        $latestData = Bill::latest('id')->first();
        $response = $this->get("/api/bills/{$latestData->id}");
        $response->json('data');
    }
    public function test_can_modify_random_bill_data_in_database()
    {
        $response = $this->get('/api/bills');
        $response->assertStatus(200);
        $data = $response->json('data');

        $length = count($data);
        $randomNumber = random_int(1, $length);

        $response = $this->get("/api/bills/{$randomNumber}");
        $oneBill = $response->json('data');

        $modifiedData = [
            "id" => $randomNumber,
            "bill_type" => "rental",
            "user_id" => $oneBill['user_id'],
            "person_id" => $oneBill['person_id'],
            "car_id" => $oneBill['car_id'],
            "total_cost" => 8888,
            "driving_distance" => 88,
            "parking_minutes" => 66,
            "driving_minutes" => 666,
            "rent_start_date" => "2024-08-30",
            "rent_start_time" => "01:44:55",
            "rent_end_date" => "2024-08-31",
            "rent_end_time" => "01:58:49",
            "invoice_date" => now()->format('Y-m-d H:i:s'),
            "invoice_status" => "pending"
        ];

        $this->putJson("/api/bills/{$randomNumber}", $modifiedData)
            ->assertStatus(200);

        $this->assertDatabaseHas('bills', [
            "id" => $randomNumber,
            "bill_type" => "rental",
            "user_id" => $oneBill['user_id'],
            "person_id" => $oneBill['person_id'],
            "car_id" => $oneBill['car_id'],
            "total_cost" => 8888,
            "driving_distance" => 88,
            "parking_minutes" => 66,
            "driving_minutes" => 666,
            "rent_start_date" => "2024-08-30",
            "rent_start_time" => "01:44:55",
            "rent_end_date" => "2024-08-31",
            "rent_end_time" => "01:58:49",
            "invoice_date" => now()->format('Y-m-d H:i:s'),
            "invoice_status" => "pending",
        ]);
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
            "driving_distance" => $bill["driving_distance"],
            "parking_minutes" => $bill["parking_minutes"],
            "driving_minutes" => $bill["driving_minutes"],
            "rent_start_date" => $bill["rent_start_date"],
            "rent_start_time" => $bill["rent_start_time"],
            "rent_end_date" => $bill["rent_end_date"],
            "rent_end_time" => $bill["rent_end_time"],
            "invoice_date" => $bill["invoice_date"],
            "invoice_status" => $bill["invoice_status"],
        ]);
    }
}
