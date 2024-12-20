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
            "autok_id" => 1,
            "status" => 1,
            "toltes_szazalek" => 6.67,
            "toltes_kw" => 1.2,
            "becsult_hatotav" => 9,
            "rendszam" => "XYZ-000",
            "km_ora_allas" => 4715,
            "gyartasi_ev" => "2019",
            
        ];

        $response = $this->postJson('/api/cars', $carData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('cars', ["rendszam" => "XXX-001"]);
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
            "toltes_szazalek" => 66.67,
            "toltes_kw" => 1.2,
            "becsult_hatotav" => 9,
            "rendszam" => "XXX-000",
            "km_ora_allas" => 4715,
            "gyartasi_ev" => 2019,
            "flotta_id_fk" => 1,
            "felsz_id_fk" => 3,
            "kategoria_besorolas_fk" => 1,
        ]);
        $updatedData = [
            "status" => 1,
            "toltes_szazalek" => 75.5,
            "toltes_kw" => 2.5,
            "becsult_hatotav" => 50,
            "rendszam" => "XXX-000",
            "km_ora_allas" => 5000,
            "gyartasi_ev" => 2020,
            "flotta_id_fk" => 1,
            "felsz_id_fk" => 3,
            "kategoria_besorolas_fk" => 1,
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
