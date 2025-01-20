<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Car;
use Tests\TestCase;

class Step5_CarControllerTest extends TestCase
{
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
            "manufacturing_year" => "2023",
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
            "manufacturing_year" => "2023",
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

        $response = $this->get("/api/cars/{$car->id}/bills");

        $response->assertStatus(200); 

        $data = collect($response->json('data'));

        if ($data->isEmpty()) {
            $this->assertTrue($data->isEmpty(), 'Ehhez az autóhoz nincs büntetés kiírva.');
        } else {
            $data->each(function ($entry) {
                $this->assertArrayHasKey('bill_type', $entry);
                $this->assertEquals('charging_penalty', $entry['bill_type']);
            });
        }
    }

    public function test_car_latest_ticket_description_text(): void
    {

        $car = Car::FirstOrFail();
        $response = $this->get("/api/cars/{$car->id}/description");

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertNotEmpty($data, 'Az adat nem érkezett meg vagy üres.');

        $this->assertArrayHasKey('admin_description', $data, 'A leírás hiányzik.');
        $this->assertIsString($data['admin_description'], 'A(z) admin_description nem szöveg.');
        $this->assertNotEmpty($data['admin_description'], 'A(z) admin_description mező üres.');
    }

    public function test_car_all_rent_history():void{
        $car = Car::FirstOrFail();
        
        $response=$this->get("api/cars/{$car->id}/renthistory");

        $response->assertStatus(200);
        $data=$response->json('data');

        $this->assertNotEmpty($data,"Nem érkezett adat a végpontról / üres!");
        $this->assertArrayHasKey('renters',$data,'A bérlők nem töltődtek be!');
        $this->assertNotEmpty($data['renters'], 'a `renters` tömb üresen érkezett vissza!. ');
    }
}
