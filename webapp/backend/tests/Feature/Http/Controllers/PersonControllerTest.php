<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Person;
use Tests\TestCase;

class PersonControllerTest extends TestCase
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
        // Lekérdezzük az összes személyt
        $response = $this->get('/api/persons');
        $response->assertJsonStructure(['data']); // Ellenőrizzük, hogy a 'data' kulcs létezik
        $response->assertStatus(200);

        // A 'data' tömböt kinyerjük
        $data = $response->json('data');
        $this->assertNotEmpty($data, 'Az adatbázisban nincsenek elérhető személyek.');

        // Véletlenszerű személy kiválasztása
        $person = fake()->randomElement($data);

        // Ellenőrizzük, hogy az összes kulcs jelen van-e a kiválasztott személyben
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

        $password = "12345678"; // Teszt jelszó

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
            "email" => fake()->unique()->lexify('??????????@gmail.com'),
        ]);
        $response->assertStatus(201);
        $responseData = $response->json('data');


        // Ellenőrizd az adatbázis többi mezőjét
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
            "email" => fake()->unique()->lexify('??????????@gmail.com'),
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
        $response = $this->get('/api/persons');
        $response->assertJsonStructure(['data']);
        $response->assertStatus(200);
    
        $data = $response->json('data');
        $this->assertNotEmpty($data, 'Nincs módosítható rekord az adatbázisban.');
    
        $person = fake()->randomElement($data);
        $this->assertArrayHasKey('person_id', $person, 'Hiányzik az ID a személy adataiból.');
    
        $updatedData = [
            "firstname" => fake()->firstName(),
            "lastname" => fake()->lastName(),
            "phone" => "+3630" . fake()->regexify('[0-9]{7}'),
            "email" => fake()->unique()->lexify('??????????@gmail.com'),
            "person_password" => "12345678",
            "id_card" => fake()->unique()->regexify('[V-Z]{2}[1-9]{1}[0-9]{5}'),
            "birth_date" => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
        ];
    
        $response = $this->putJson("/api/persons/{$person['person_id']}", $updatedData);
        $response->assertStatus(200);
    
        $this->assertDatabaseHas('persons', array_merge($updatedData, [
            'id' => $person['person_id'],
        ]));
    }


    public function test_can_delete_person_from_database()
    {
        $response = $this->get('/api/persons');
        $response->assertJsonStructure(['data']);
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertNotEmpty($data, 'Nincs törölhető rekord az adatbázisban.');

        $person = fake()->randomElement($data);
        $this->assertArrayHasKey('person_id', $person, 'Hiányzik az ID a személy adataiból.');

        $response = $this->get("/api/persons/{$person['person_id']}");
        $response->assertStatus(200);

        $response = $this->delete("/api/persons/{$person['person_id']}");
        $response->assertStatus(204);

        $this->assertDatabaseMissing('persons', ['id' => $person['person_id']]);
    }
}
