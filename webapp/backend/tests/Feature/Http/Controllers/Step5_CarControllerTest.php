<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Car;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class Step5_CarControllerTest extends TestCase
{
    use DatabaseTransactions;
    public function test_can_get_all_cars(): void
    {
        $response = $this->getJson('/api/cars');
        $response->assertStatus(200);

        $data = $response->json('data');
        $response = $this->assertNotEmpty($data);
    }

    public function test_can_create_fake_car_data()
    {
        $carData = [
            "plate" => "XyZ-090",
            "power_percent" => 13.06,
            "power_kw" => 4.7,
            "estimated_range" => 34.6,
            "status" => 6,
            "category_id" => 3,
            "equipment_class" => 3,
            "fleet_id" => 3,
            "odometer" => 28252,
            "manufactured" => "2023",
            "carmodel" => "e-up!"
        ];

        $response = $this->postJson('/api/cars', $carData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('cars', ["plate" => "XyZ-090"]);
    }


    public function test_put_previous_fake_car_modifing()
    {
        $latestCardata = Car::latest('id')->first();

        $modifiedData = [
            "id" => $latestCardata->id,
            "plate" => "XyZ-090",
            "power_percent" => 13.06,
            "power_kw" => 4.7,
            "estimated_range" => 34.6,
            "status" => 6,
            "category_id" => 3,
            "equipment_class" => 3,
            "fleet_id" => 3,
            "odometer" => 28252,
            "manufactured" => "2023",
        ];

        $response = $this->put("api/cars/{$latestCardata->id}", $modifiedData);
        $response->assertStatus(200);
    }
    public function test_delete_fake_fleet_type_from_db(): void
    {
        $latestCardata = Car::latest('id')->first();

        $response = $this->delete("api/cars/{$latestCardata->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing(
            'cars',
            [
                'id' => $latestCardata->id,
            ]
        );
    }
    public function test_one_car_all_charging_penalty_bills(): void
    {
        $car = Car::firstOrFail();

        $response = $this->get("/api/cars/{$car->id}/fees");

        $response->assertStatus(200);

        $data = collect($response->json('data'));

        $car = Car::firstOrFail();

        $response = $this->get("/api/cars/{$car->id}/fees");
        $response->assertStatus(200);

        $data = $response->json('data');

        if (isset($data['error'])) {
            $this->assertEquals(
                'Fine has not found for this car',
                $data['error']
            );
            return;
        }
        $this->assertArrayHasKey('fine_types', $data);
        $this->assertEquals('charging_penalty', $data['fine_types']);
    }

    public function test_car_latest_ticket_description_text(): void
    {
        $car = Car::firstOrFail();
        $ticketData = [
            "car_id" => $car->id,
            "description" => "valahol ikszdé",
            "status_id" => 6
        ];
        $this->postJson("/api/tickets", $ticketData)
            ->assertStatus(201);
        $response = $this->getJson("/api/cars/{$car->id}/description");
        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
        $responseData = $response->json('data');
        $this->assertEquals("valahol ikszdé", $responseData['admin_description']);
    }

    public function test_car_all_rent_history(): void
    {
        $car = Car::factory()->create([
            'plate' => 'TEST' . rand(1000, 9999),
            'status' => 1
        ]);
        $user = User::factory()->create([
            'user_name' => 'TestUser_' . uniqid()
        ]);
        $car->users()->attach($user->id, [
            'start_percent' => 70.28,
            'start_kw' => 25.3,
            'end_percent' => 64.17,
            'end_kw' => 23.1,
            'rent_start' => now()->subDays(5),
            'rent_close' => now()->subDays(4),
            'distance' => 50,
            'parking_minutes' => 60,
            'driving_minutes' => 120,
            'rental_cost' => 15000,
            'category_id' => $car->category_id,
            'rentstatus' => 2,
            'invoice_date' => now()
        ]);

        $response = $this->get("api/cars/{$car->id}/renthistory");

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertNotEmpty($data, "Nem érkezett adat a végpontról / üres!");
        $this->assertArrayHasKey('renters', $data, 'A bérlők nem töltődtek be!');
        $this->assertNotEmpty($data['renters'], 'A `renters` tömb üresen érkezett vissza!');

        $renters = $data['renters'];
        $this->assertIsArray($renters);

        foreach ($renters as $renter) {
            $this->assertArrayHasKey('rent_id', $renter);
            $this->assertArrayHasKey('user', $renter);
            $this->assertArrayHasKey('rent_start', $renter);
            $this->assertArrayHasKey('start_percent', $renter);
            $this->assertArrayHasKey('start_kw', $renter);
            $this->assertArrayHasKey('rent_close', $renter);
            $this->assertArrayHasKey('end_percent', $renter);
            $this->assertArrayHasKey('end_kw', $renter);
            $this->assertArrayHasKey('distance', $renter);
            $this->assertArrayHasKey('rental_cost', $renter);
            $this->assertArrayHasKey('parking', $renter);
            $this->assertArrayHasKey('invoice_date', $renter);

            ## Ellenőrizzük a konkrét értékeket, de most a user_name-t dinamikusan
            $this->assertEquals($user->user_name, $renter['user']);
            $this->assertEquals(70.28, $renter['start_percent']);
            $this->assertEquals(25.3, $renter['start_kw']);
            $this->assertEquals(64.17, $renter['end_percent']);
            $this->assertEquals(23.1, $renter['end_kw']);
            $this->assertEquals(50, $renter['distance']);
            $this->assertEquals('15 000', $renter['rental_cost']);
            $this->assertEquals(60, $renter['parking']);
        }
    }
}
