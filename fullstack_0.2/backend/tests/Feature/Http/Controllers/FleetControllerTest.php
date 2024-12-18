<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Fleet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FleetControllerTest extends TestCase
{
    public function test_get_all_fleet_types(): void
    {
        $response = $this->get('api/fleets');
        $response->assertStatus(200);
    }
    public function test_post_fake_fleet_type_to_db(): void
    {
        $data = [
            "gyarto" => "Renault",
            "tipus" => "UI-UX-ULTRA",
            "teljesitmeny" => 100,
            "vegsebesseg" => 300,
            "gumimeret" => "165|65-R15",
            "hatotav" => 445
        ];
        $response = $this->post('api/fleets', $data);
        $response->assertStatus(201);

        $this->assertDatabaseHas('fleets', [
            "gyarto" => "Renault",
            "tipus" => "UI-UX-ULTRA",
            "teljesitmeny" => 100,
            "vegsebesseg" => 300,
            "gumimeret" => "165|65-R15",
            "hatotav" => 445
        ]);
    }
    public function test_delete_fake_fleet_type_from_db(): void
    {
        $data = [
            "gyarto" => "Renault",
            "tipus" => "UI-UX-ULTRA",
            "teljesitmeny" => 100,
            "vegsebesseg" => 300,
            "gumimeret" => "165|65-R15",
            "hatotav" => 445
        ];
        ### Most ezt a kamu adatot töröljük is ki!
        $latestFleet = Fleet::latest('id')->first();
        $response = $this->delete("api/fleets/{$latestFleet->id}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('fleets',
            [
                'id' => $latestFleet->id,
            ]
        );
    }
}