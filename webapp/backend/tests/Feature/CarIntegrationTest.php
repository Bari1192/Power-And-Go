<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Fleet;
use Tests\TestCase;

class CarIntegrationTest extends TestCase
{
    public function test_can_add_new_car_with_new_fleet_type_new_category_and_new_equipment_type_adn_set_rentable(): void
    {
        $data = [
            "manufacturer" => "Maserati",
            "carmodel" => "MC20",
            "motor_power" => 500,
            "top_speed" => 300,
            "tire_size" => "170|70-R17",
            "driving_range" => 1000
        ];
        $response = $this->postJson('api/fleets', $data);
        $response->assertStatus(201);

        $newFleetType = Fleet::latest('id')->first()->toArray();

        $this->assertDatabaseHas('fleets', [
            "id" => $newFleetType['id'],
            "manufacturer" => "Maserati",
            "carmodel" => "MC20",
            "motor_power" => 500,
            "top_speed" => 300,
            "tire_size" => "170|70-R17",
            "driving_range" => 1000
        ]);

        $data = [
            "category_class" => $newFleetType['id'],
            "motor_power" => $newFleetType['motor_power'],
        ];
        $response = $this->postJson('api/categories', $data);
        $response->assertStatus(201);

        $newCategoryType = Category::latest('id')->first();

        $this->assertDatabaseHas('categories', [
            "id" => $newCategoryType->id,
        ]);
        ## 3. Új Equipment tipust létrehozni
        $newEquipment = Equipment::create([
            "reversing_camera" => 1,
            "lane_keep_assist" => 1,
            "adaptive_cruise_control" => 1,
            "parking_sensors" => 1,
            "multifunction_wheel" => 1,
        ]);

        $this->assertDatabaseHas('equipments', $newEquipment->toArray());

        $newEquipmentGet = Equipment::latest('id')->first();

        ## 4. Új Car-t létrehozni és átadni ezeket neki.
        #### 5. Végül elérhető / bérelhető státuszba rakni.
        $newCarType = [
            "plate" => "XXXX-" . fake()->regexify("[0-9]{3}"),
            "power_percent" =>floatval(100.0),
            "power_kw" => 99.9,
            "estimated_range" => 99.9,
            "status" => 1,
            "category_id" => $newCategoryType->id, 
            "equipment_class" => $newEquipmentGet->id,
            "fleet_id" => $newFleetType['id'],
            "odometer" => 0,
            "manufactured" => date('Y'),
        ];

        $response = $this->postJson('/api/cars', $newCarType);
        $response->assertStatus(201);
    }
}
