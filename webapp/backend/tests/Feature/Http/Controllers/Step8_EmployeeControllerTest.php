<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Person;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class Step8_EmployeeControllerTest extends TestCase
{
    use DatabaseTransactions;
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
        $response = $this->assertArrayHasKey('hire_date', $oneEmployee, 'Az `hire_date` érték nem sikerült lekérni.');
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
        $response = $this->assertArrayHasKey('hire_date', $oneEmployee, 'Az `hire_date` érték nem sikerült lekérni.');
    }
    public function test_cannot_create_employee_without_existing_person_data()
    {
        $response = $this->postJson('/api/persons', [
            "person_password" => "12345678",
            "id_card" => fake()->unique()->regexify('[V-Z]{2}[1-9]{1}[0-9]{5}'),
            "firstname" => fake()->firstName(),
            "lastname" => fake()->lastName(),
            "birth_date" => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            "phone" => "+3630" . fake()->regexify('[0-9]{7}'),
            "email" => fake()->regexify('[a-z0-9]{18}') . '@gmail.com'
        ]);
        $response->assertStatus(201);

        $lastInsertedID = Person::latest('id')->first()->id;
        $response = $this->get("/api/persons/{$lastInsertedID}");
        $response->assertStatus(200);

        $response->assertJsonPath('data.driving_license', null);
        $response->assertJsonPath('data.license_start_date', null);
        $response->assertJsonPath('data.license_end_date', null);


        $employee = [
            "field" => "Marketing",
            "role" => 'Social Media kezelő',
            "position" => "Munkatárs",
            "salary" => fake()->numberBetween(200_000, 500_000),
            "salary_type" => "fix",
            "hire_date" => now()->format("Y-m-d"),
        ];
        $response = $this->postJson('/api/employees', $employee);
        $response->assertStatus(422);
    }
    public function test_can_create_new_employee_into_database()
    {
        $personResponse = $this->postJson('/api/persons', [
            "person_password" => "12345678",
            "id_card" => fake()->unique()->regexify('[V-Z]{2}[1-9]{1}[0-9]{5}'),
            "firstname" => fake()->firstName(),
            "lastname" => fake()->lastName(),
            "birth_date" => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            "phone" => "+3630" . fake()->regexify('[0-9]{7}'),
            "email" => fake()->regexify('[a-z0-9]{18}') . '@gmail.com'
        ]);

        $personResponse->assertStatus(201);
        $personData = $personResponse->json('data');
        $this->assertNotNull($personData, 'Person data not found in response.');

        $newEmployeeData = [
            "person_id" => $personData['person_id'],
            "field" => "Marketing",
            "role" => "Social Media kezelő",
            "position" => "Munkatárs",
            "salary_type" => "fix",
            "salary" => fake()->numberBetween(200000, 500000),
            "hire_date" => now()->format('Y-m-d'),
        ];

        $employeeResponse = $this->postJson('/api/employees', $newEmployeeData);
        $employeeResponse->assertStatus(201);

        $this->assertDatabaseHas('employees', [
            'person_id' => $newEmployeeData['person_id'],
            'field' => $newEmployeeData['field'],
            'role' => $newEmployeeData['role'],
            'position' => $newEmployeeData['position'],
            'salary_type' => $newEmployeeData['salary_type'],
            'salary' => $newEmployeeData['salary'],
            'hire_date' => $newEmployeeData['hire_date'],
        ]);
    }
    public function test_can_modify_random_employee_from_database()
    {
        $response = $this->get("/api/employees");
        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertNotEmpty($data, 'Nincs elérhető dolgozó az adatbázisban.');

        $randomEmployee = fake()->randomElement($data);
        $employeeId = $randomEmployee['id'];

        $response = $this->get("/api/employees/{$employeeId}");
        $response->assertStatus(200);
        $employee = $response->json('data');

        $updatedData = [
            "id" => $employee['id'],
            "person_id" => $employee['person_id'],
            "field" => "Ügyfélszolgálat",
            "role" => 'Panaszkezelés',
            "position" => "Munkatárs",
            "salary_type" => "fix",
            "salary" => 400000,
            "hire_date" => now()->format("Y-m-d"),
        ];

        $response = $this->putJson("/api/employees/{$employeeId}", $updatedData);
        $response->assertStatus(200);
    }
    public function test_can_delete_random_employee_from_database_table()
    {
        $response = $this->get("/api/employees");
        $response->assertStatus(200);
        $data = $response->json('data');
    
        $this->assertNotEmpty($data, 'Nincs elérhető dolgozó az adatbázisban.');
    
        $randomEmployee = fake()->randomElement($data);
        $employeeId = $randomEmployee['id'];
    
        $response = $this->get("/api/employees/{$employeeId}");
        $response->assertStatus(200);
        $employee = $response->json('data');
    
        $response = $this->delete("/api/employees/{$employeeId}");
        $response->assertStatus(204);
    
        $response = $this->assertDatabaseMissing('employees', $employee);
    }
}
