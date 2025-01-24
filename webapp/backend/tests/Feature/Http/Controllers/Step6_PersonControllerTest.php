<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Person;
use Tests\TestCase;

class Step6_PersonControllerTest extends TestCase
{
    public function test_can_get_all_person_data(): void
    {

        $response = $this->get('/api/persons');
        $response->assertStatus(200);

        $response->assertJsonStructure(['data']);
        $response = $this->assertNotEmpty('data');
    }

    public function test_can_get_choosen_person_data(): void
    {
        $response = $this->get('/api/persons');
        $response->assertJsonStructure(['data']);

        $lenght = count($response->json('data'));
        $choose = fake()->numberBetween(1, $lenght);
        $response = $this->get("/api/persons/{$choose}");
        $response->assertJsonStructure(['data']);
    }

    public function test_can_get_random_person_all_keys_from_database()
    {
        $response = $this->get('/api/persons');
        $response->assertJsonStructure(['data']);
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertNotEmpty($data, 'Az adatbázisban nincsenek elérhető személyek.');

        $person = fake()->randomElement($data);

        $expectedDataKeys = [
            'person_id',
            'person_password',
            'id_card',
            'driving_license',
            'license_start_date',
            'license_end_date',
            'firstname',
            'lastname',
            'birth_date',
            'phone',
            'email',
        ];

        foreach ($expectedDataKeys as $key) {
            $this->assertArrayHasKey($key, $person, "Hiányzik a kulcsmező: {$key}");
        }
    }
    public function test_can_create_new_person_into_database_with_driving_license_data()
    {

        $password = "12345678";

        $response = $this->postJson('/api/persons', [
            "person_password" => $password,
            "id_card" => fake()->unique()->regexify('[V-Z]{2}[1-9]{1}[0-9]{5}'),
            "driving_license" => fake()->unique()->regexify('[V-Z]{2}[0-9]{6}'),
            "license_start_date" => "2016-01-01",
            "license_end_date" => "2026-01-01",
            "firstname" => fake()->firstName(),
            "lastname" => fake()->lastName(),
            "birth_date" => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            "phone" => "+3630" . fake()->regexify('[0-9]{7}'),
            "email" => fake()->regexify('[a-z0-9]{18}') . '@gmail.com',
        ]);
        $response->assertStatus(201);
        $responseData = $response->json('data');

        $this->assertDatabaseHas('persons', [
            'id_card' => $responseData['id_card'],
            'driving_license' => $responseData['driving_license'],
            'license_start_date' => $responseData['license_start_date'],
            'license_end_date' => $responseData['license_end_date'],
            'firstname' => $responseData['firstname'],
            'lastname' => $responseData['lastname'],
            'birth_date' => $responseData['birth_date'],
            'phone' => $responseData['phone'],
            'email' => $responseData['email'],
        ]);
    }
    public function test_can_create_new_person_into_database_without_driving_license_data()
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
    }
    public function test_can_update_person_in_database()
    {
        // Step 1: Create a new person
        $response = $this->postJson('/api/persons', [
            "person_password" => "12345678",
            "id_card" => fake()->unique()->regexify('[V-Z]{2}[1-9]{1}[0-9]{5}'),
            "firstname" => fake()->firstName(),
            "lastname" => fake()->lastName(),
            "driving_license" => fake()->unique()->regexify('[A-Z]{2}[1-9][0-9]{5}'),
            "license_start_date" => "2006-01-01",
            "license_end_date" => "2016-01-01",
            "birth_date" => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            "phone" => "+3630" . fake()->regexify('[0-9]{7}'),
            "email" => fake()->regexify('[a-z0-9]{18}') . '@gmail.com'
        ]);

        $response->assertStatus(201);

        // Retrieve the inserted person data
        $inserted = $response->json('data');
        $this->assertNotNull($inserted, 'Failed to retrieve the inserted data.');
        $this->assertArrayHasKey('person_id', $inserted, 'Person ID is missing in the response.');

        // Step 2: Prepare updated data
        $updatedData = [
            "person_password" => "87654321",
            "id_card" => fake()->unique()->regexify('[V-Z]{2}[1-9]{1}[0-9]{5}'),
            "firstname" => fake()->firstName(),
            "lastname" => fake()->lastName(),
            "driving_license" => fake()->unique()->regexify('[A-Z]{2}[1-9][0-9]{5}'),
            "license_start_date" => "2010-01-01",
            "license_end_date" => "2020-01-01",
            "birth_date" => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            "phone" => "+3630" . fake()->regexify('[0-9]{7}'),
            "email" => fake()->regexify('[a-z0-9]{15}') . '@gmail.com',
        ];
        $lastPersonId=$inserted['person_id'];
        $response = $this->putJson("/api/persons/$lastPersonId", $updatedData);

        $this->assertDatabaseHas('persons', [
            'id' => $inserted['person_id'],
            'id_card' => $inserted['id_card'],
            'firstname' => $inserted['firstname'],
            'lastname' => $inserted['lastname'],
            'phone' => $inserted['phone'],
            'email' => $inserted['email'],
            'license_start_date' => $inserted['license_start_date'],
            'license_end_date' => $inserted['license_end_date'],
        ]);
    }


    public function test_can_delete_person_from_database()
    {
        $response = $this->get('/api/persons');
        $response->assertJsonStructure(['data']);
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertNotEmpty($data, 'No deletable records found in the database.');

        $person = fake()->randomElement($data);
        $this->assertIsArray($person, 'Invalid person data.');
        $this->assertArrayHasKey('person_id', $person, 'Missing person_id in person data.');

        $response = $this->delete("/api/persons/{$person['person_id']}");
        $response->assertStatus(204);

        $this->assertDatabaseMissing('persons', ['id' => $person['person_id']]);
    }
}
