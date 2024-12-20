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
            "rendszam" => "XXX-111",
            "kilometerora" => 4715,
            "gyartasi_ev" => 2019,
            "flotta_azon" => 1,
            "felszereltseg" => 3,
            "kategoria" => 1,
            
        ];

        $response = $this->postJson('/api/cars', $carData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('cars', ["rendszam" => "XXX-111"]);
    }



    public function delete_car(): void
    {
        $response = $this->delete('/api/cars/111');
        $response->assertStatus(204);
    }

    public function update_car_data(): void
    {
        $car = Car::factory()->create([
            "status" => 1,
            "toltes_szaz" => 66.67,
            "toltes_kw" => 1.2,
            "becs_tav" => 9,
            "rendszam" => "XXX-000",
            "kilometerora" => 4715,
            "gyartasi_ev" => 2019,
            "flotta_azon" => 1,
            "felszereltseg" => 3,
            "kategoria" => 1,
        ]);
        $updatedData = [
            "status" => 1,
            "toltes_szaz" => 66.67,
            "toltes_kw" => 1.2,
            "becs_tav" => 9,
            "rendszam" => "XXX-000",
            "kilometerora" => 4715,
            "gyartasi_ev" => 2019,
            "flotta_azon" => 1,
            "felszereltseg" => 3,
            "kategoria" => 1,
        ];

        $response = $this->putJson("/api/cars/{$car->autok_id}", $updatedData);

        // Ellenőrizd a válasz státuszt
        $response->assertStatus(201);

        // Ellenőrizd a válasz tartalmát
        $response->assertJsonFragment(array_merge($updatedData, ['autok_id' => $car->autok_id]));

        // Ellenőrizd az adatbázis rekordot
        $this->assertDatabaseHas('autok', array_merge($updatedData, ['autok_id' => $car->autok_id]));
        $response->assertJsonFragment([
            "autok_id" => $car->autok_id,
            "status" => [
                "status_name" => "Szabad",
                "status_descrip" => "Az autó elérhető és bérlésre kész.",
            ],
        ]);
    }
}
