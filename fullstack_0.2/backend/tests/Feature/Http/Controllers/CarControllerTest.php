<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Car;
use Tests\TestCase;


class CarControllerTest extends TestCase
{
    public function test_can_get_all_cars(): void
    {
        $response = $this->getJson('/api/cars');
        $response->assertStatus(200);
    }

    public function test_can_create_fake_car_data()
    {
        $carData = [
            "status" => 1,
            "toltes_szaz" => 66.67,
            "toltes_kw" => 1.2,
            "becs_tav" => 9.5,
            "rendszam" => "XYZ-999",
            "kilometerora" => 4715,
            "gyartasi_ev" => "2019",
            "flotta_azon" => 1,
            "felszereltseg" => 3,
            "kategoria" => 1
        ];

        $response = $this->postJson('/api/cars', $carData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('cars', ["rendszam" => "XYZ-999"]);
    }


    public function test_put_previous_fake_car_modifing()
    {
        $latestCardata = Car::latest('id')->first();

        $modifiedData = [
            "id" => $latestCardata->id,
            "status" => 1,
            "toltes_szaz" => 66.67,
            "toltes_kw" => 1.2,
            "becs_tav" => 9.5,
            "rendszam" => "XXX-111",
            "kilometerora" => 4715,
            "gyartasi_ev" => "2019",
            "flotta_azon" => 1,
            "felszereltseg" => 3,
            "kategoria" => 1,
        ];

        $response = $this->put("api/cars/{$latestCardata->id}", $modifiedData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('cars', array_merge(['id' => $latestCardata->id], $modifiedData));
    }
    public function test_delete_fake_fleet_type_from_db(): void
    {
        ### Most az előző kamu adatot töröljük is ki!
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
}
