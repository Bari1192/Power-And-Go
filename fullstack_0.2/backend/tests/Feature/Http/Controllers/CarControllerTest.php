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
            "rendszam" => "XyZ-090",
            "toltes_szaz" => 13.06,
            "toltes_kw" => 4.7,
            "becs_tav" => 34.6,
            "status" => 6,
            "kategoria" => 3,
            "felszereltseg" => 3,
            "flotta_azon" => 3,
            "kilometerora" => 28252,
            "gyartasi_ev" => "2023",
            "tipus" => "e-up!"
        ];

        $response = $this->postJson('/api/cars', $carData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('cars', ["rendszam" => "XyZ-090"]);
    }


    public function test_put_previous_fake_car_modifing()
    {
        $latestCardata = Car::latest('id')->first();

        $modifiedData = [
            "id" => $latestCardata->id,
            "rendszam" => "XyZ-090",
            "toltes_szaz" => 13.06,
            "toltes_kw" => 4.7,
            "becs_tav" => 34.6,
            "status" => 6,
            "kategoria" => 3,
            "felszereltseg" => 3,
            "flotta_azon" => 3,
            "kilometerora" => 28252,
            "gyartasi_ev" => "2023",
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
}
