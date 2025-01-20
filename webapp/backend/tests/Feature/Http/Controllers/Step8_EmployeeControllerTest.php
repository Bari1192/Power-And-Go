<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Employee;
use Tests\TestCase;

class Step8_EmployeeControllerTest extends TestCase
{
    public function test_can_get_all_employee_data()
    {
        $response = $this->get('/api/employees');
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertNotEmpty($data, 'Az adatbázisban nincsenek elérhető dolgozók.');
    }
    public function test_can_get_random_employee_data_with_array_rand()
    {
        $response = $this->get('/api/employees');
        $response->assertStatus(200);
        $data = $response->json('data');

        $oneEmployee = fake()->randomElement($data);

        $response = $this->assertArrayHasKey('id', $oneEmployee, 'Az `id` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('person_id', $oneEmployee, 'Az `person_id` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('field', $oneEmployee, 'Az `field` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('role', $oneEmployee, 'Az `role` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('position', $oneEmployee, 'Az `position` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('salary_type', $oneEmployee, 'Az `salary_type` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('salary', $oneEmployee, 'Az `salary` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('start_date', $oneEmployee, 'Az `start_date` érték nem sikerült lekérni.');
    }
    public function test_can_get_random_employee_data_with_api()
    {
        $response = $this->get('/api/employees');
        $response->assertStatus(200);
        $data = $response->json('data');

        $lenght = count($data);
        $randomNumber = random_int(1, $lenght);

        $response = $this->get("/api/employees/{$randomNumber}");
        $oneEmployee = $response->json('data');

        $response = $this->assertArrayHasKey('id', $oneEmployee, 'Az `id` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('person_id', $oneEmployee, 'Az `person_id` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('field', $oneEmployee, 'Az `field` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('role', $oneEmployee, 'Az `role` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('position', $oneEmployee, 'Az `position` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('salary_type', $oneEmployee, 'Az `salary_type` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('salary', $oneEmployee, 'Az `salary` érték nem sikerült lekérni.');
        $response = $this->assertArrayHasKey('start_date', $oneEmployee, 'Az `start_date` érték nem sikerült lekérni.');
    }
    public function test_cannot_create_employee_without_existing_person_data()
    {
        $employee = [
            "field" => "Marketing",
            "role" => 'Social Media kezelő',
            "position" => array_rand(["Munkatárs", "Supervisor", "Főosztályvezető", "Felsővezető",]),
            "salary" => fake()->numberBetween(200_000, 500_000),
            "salary_type" => "fix",
            "start_date" => now(),
        ];
        $response = $this->postJson('/api/employees', $employee);
        $response->assertStatus(422);
    }
    public function test_can_create_new_employee_into_database()
    {
        $response = $this->postJson('/api/employees', [
            "person_id" => 500,
            "field" => "Marketing",
            "role" => 'Social Media kezelő',
            "position" => "Munkatárs",
            "salary_type" => "fix",
            "salary" => fake()->numberBetween(200000, 500000),
        ]);
        $response->assertStatus(201);
    
        $latestEmployee = Employee::latest('id')->first();
        $response=$this->get("/api/employees/{$latestEmployee->id}");
        $response->assertStatus(200);
        $response = $response->json('data');

        $this->assertDatabaseHas('employees', [
            "id"=>$latestEmployee->id,
            "person_id" => 500,
            "field" => "Marketing",
            "role" => 'Social Media kezelő',
        ]);
    }
    public function test_can_modify_random_employee_from_database() 
    {
        $response=$this->get("/api/employees");
        $response->assertStatus(200);
        $data = $response->json('data');

        $lenght = count($data);
        $randomNumber = random_int(1, $lenght);
        $response=$this->get("/api/employees/{$randomNumber}");
        $response->assertStatus(200);
        $employee = $response->json('data');

        $updatetedData=[
            "id"=>$employee['id'],
            "person_id"=>$employee['person_id'],
            "field" => "Ügyfélszolgálat",
            "role" => 'Panaszkezelés',
            "position" => "Munkatárs",
            "salary_type" => "fix",
            "salary" => 400000,
        ];
        $response=$this->putJson("/api/employees/{$randomNumber}",$updatetedData);
        $response->assertStatus(200);
    }
    public function test_can_delete_random_employee_from_database_table() 
    {
        $response=$this->get("/api/employees");
        $response->assertStatus(200);
        $data = $response->json('data');

        $lenght = count($data);
        $randomNumber = random_int(1, $lenght);
        $response=$this->get("/api/employees/{$randomNumber}");
        $response->assertStatus(200);
        $employee = $response->json('data');

        $response=$this->delete("/api/employees/{$randomNumber}");
        $response->assertStatus(204);

        $response=$this->assertDatabaseMissing('employees',$employee);
    }
}
