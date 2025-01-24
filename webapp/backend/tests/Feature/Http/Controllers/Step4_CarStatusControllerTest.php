<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\CarStatus;
use Tests\TestCase;

class Step4_CarStatusControllerTest extends TestCase
{
    public function test_get_all_carstatus_data(): void
    {
        $response = $this->get('/api/carstatus');
        $response->assertStatus(200);

        $data = $response->json('data');
        $response = $this->assertNotEmpty($data);
    }
    public function test_can_get_first_object_with_free_status(): void
    {
        $response = $this->get('/api/carstatus');
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $response->assertJsonPath('data.0.status_name', 'Szabad');
    }
    public function test_can_get_second_object_with_reserved_status(): void
    {
        $response = $this->get('/api/carstatus');
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $response->assertJsonPath('data.1.status_name', 'Foglalva');
    }
    public function test_can_get_second_object_with_rent_in_process_status(): void
    {
        $response = $this->get('/api/carstatus');
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $response->assertJsonPath('data.2.status_name', 'Bérlés alatt');
    }
    public function test_can_get_second_object_with_in_car_accident_problem_status(): void
    {
        $response = $this->get('/api/carstatus');
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $response->assertJsonPath('data.3.status_name', 'Baleset miatt kivonva');
    }
    public function test_can_get_second_object_with_waiting_for_mechanic_status(): void
    {
        $response = $this->get('/api/carstatus');
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $response->assertJsonPath('data.4.status_name', 'Szervízre vár');
    }
    public function test_can_get_second_object_with_waiting_for_cleaning_status(): void
    {
        $response = $this->get('/api/carstatus');
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $response->assertJsonPath('data.5.status_name', 'Tisztításra vár');
    }
    public function test_can_get_second_object_with_critical_low_battery_status(): void
    {
        $response = $this->get('/api/carstatus');
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $response->assertJsonPath('data.6.status_name', 'Kritikus töltés');
    }

    public function test_can_post_new_carstatus_data()
    {

        $createdCarstatus = [
            "status_name" => "TestTest",
            "status_descrip" => "test test test"
        ];

        $response = $this->post('/api/carstatus', $createdCarstatus);
        $response->assertStatus(201);

        $carstatus=CarStatus::latest('id')->first();
        $this->assertDatabaseHas('carstatus', [
            "id" => $carstatus->id,
            "status_name" => 'TestTest',
            "status_descrip" => "test test test"
        ]);
    }


    public function test_can_delete_carstatus_data()
    {
        $carstatus = CarStatus::latest('id')->first();

        $this->assertNotNull($carstatus, 'No CarStatus found to delete.');
        $response = $this->delete("api/carstatus/{$carstatus->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('carstatus', [
        'id' => $carstatus->id,
        ]);
    }
}
