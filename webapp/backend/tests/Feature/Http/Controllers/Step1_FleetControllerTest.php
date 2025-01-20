<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Fleet;
use Tests\TestCase;

class Step1_FleetControllerTest extends TestCase
{
    public function test_get_all_fleet_types(): void
    {
        $response = $this->get('api/fleets');
        $response->assertStatus(200);

        $data = $response->json('data');
        $response = $this->assertNotEmpty($data);
    }
    public function test_post_fake_fleet_type_to_db(): void
    {
        $data = [
            "manufacturer" => "Renault",
            "carmodel" => "UI-UX-ULTRA",
            "motor_power" => 100,
            "top_speed" => 300,
            "tire_size" => "165|65-R15",
            "driving_range" => 445
        ];
        $response = $this->post('api/fleets', $data);
        $response->assertStatus(201);

        $this->assertDatabaseHas('fleets', [
            "manufacturer" => "Renault",
            "carmodel" => "UI-UX-ULTRA",
            "motor_power" => 100,
            "top_speed" => 300,
            "tire_size" => "165|65-R15",
            "driving_range" => 445
        ]);
    }
    public function test_put_previous_fake_fleet_modifing()
    {
        $latestFleet = Fleet::latest('id')->first();

        $modifiedData = [
            "id"=>$latestFleet->id,
            "manufacturer" => "Renault",
            "carmodel" => "MODIFIED-ULTRA-SUPER",
            "motor_power" => 100,
            "top_speed" => 300,
            "tire_size" => "165|65-R15",
            "driving_range" => 445
        ];

        $response = $this->put("api/fleets/{$latestFleet->id}", $modifiedData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('fleets', array_merge(['id' => $latestFleet->id], $modifiedData));
    }

    public function test_delete_fake_fleet_type_from_db(): void
    {
        $latestFleet = Fleet::latest('id')->first();

        $response = $this->delete("api/fleets/{$latestFleet->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing(
            'fleets',
            [
                'id' => $latestFleet->id,
            ]
        );
    }
}
