<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

// Auth miatt egyelőre a teszt elbukik, míg nem javítom.

class Step7_UserController extends TestCase
{
    use DatabaseTransactions;

    public function test_can_get_all_user_data()
    {
        $registerResponse = $this->post('/api/register', [
            "person_password" => "12345678",
            "id_card" => "XXX99999CD",
            "driving_license" => "DXR99999",
            "license_start_date" => "2024-01-01",
            "license_end_date" => "2034-01-01",
            "firstname" => "asd",
            "lastname" => "asd",
            "birth_date" => "1990-01-01",
            "phone" => "+36999888999",
            "email" => "valami123@gmail.com",
            "user_name" => "tesztesetKetto",
            "pin" => "1234",
            "role" => "admin",
            "sub_id" => 1
        ]);
        $loginResponse = $this->post('/api/authenticatelogin', [
            'email' => "valami123@gmail.com",
            'password' => "12345678"
        ]);
        $token = $loginResponse->json('data.token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get('/api/users');
    
        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertNotEmpty($data);
    }

    public function test_cannot_create_user_without_existing_person()
    {
        $user = [
            "user_name" => fake()->regexify('[A-Za-z]{10}[0-9]{3}'),
            "password" => fake()->regexify('[A-Za-z]{6}[0-9]{2}'),
            "password_2_4" => fake()->regexify('[0-9]{2}'),
            "account_balance" => 0,
            "sub_id" => 1,
            'vip_discount' => 1,
            'bonus_minutes' => 0,
            'plant_tree' => 1,
            'bonus_min_exp' => now()->format('Y-m-d'),
            'driving_minutes' => 0,
            'account_balance' => 0,
            'contributions' => 0,
        ];

        $response = $this->postJson('/api/users', $user);
        $response->assertStatus(422);
    }
    public function test_password_2_4_is_correctly_stored_in_database()
    {
        $pwd = "12345678";
        $person = Person::create([
            "person_password" => $pwd,
            "id_card" => fake()->unique()->regexify('[V-Z]{2}[1-9]{1}[0-9]{5}'),
            "firstname" => fake()->firstName(),
            "lastname" => fake()->lastName(),
            "birth_date" => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            "phone" => "+3630" . fake()->regexify('[0-9]{7}'),
            "email" => fake()->unique()->lexify('??????????@gmail.com'),
        ]);
        $pin = "9876";
        $response = $this->postJson('/api/users', [
            'person_id' => $person->id,
            'user_name' => $person['firstname'] . $person['lastname']. fake()->regexify('[0-9]{5}'),
            'pin' => $pin,
            'password_2_4' => $pin[1] . $pin[3],
            'account_balance' => 0,
            'sub_id' => 1,
            'vip_discount' => 1,
            'bonus_minutes' => 0,
            'plant_tree' => 1,
            'bonus_min_exp' => now()->format('Y-m-d'),
            'driving_minutes' => 0,
            'account_balance' => 0,
            'contributions' => 0,
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'person_id' => $person->id,
            'password_2_4' => $pin[1] . $pin[3],
            'vip_discount' => 1,
            'bonus_minutes' => 0,
            'plant_tree' => 1,
            'bonus_min_exp' => now()->format('Y-m-d'),
            'driving_minutes' => 0,
            'account_balance' => 0,
            'contributions' => 0,
        ]);
    }
    public function test_can_delete_user_and_associated_person_from_database()
    {
        $person = Person::create([
            "person_password" => fake()->regexify('[0-9]{8}'),
            "id_card" => fake()->unique()->regexify('[V-Z]{2}[1-9]{1}[0-9]{5}'),
            "firstname" => fake()->firstName(),
            "lastname" => fake()->lastName(),
            "birth_date" => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            "phone" => "+3630" . fake()->regexify('[0-9]{7}'),
            "email" => fake()->unique()->lexify('??????????@gmail.com'),
        ]);

        $user = User::create([
            "person_id" => $person->id,
            "user_name" => fake()->userName(),
            "pin" => fake()->regexify('[0-9]{4}'),
            "password_2_4" => $person->person_password[1] . $person->person_password[3],
            "account_balance" => fake()->numberBetween(1000, 100000),
            "sub_id" => 1,
            'vip_discount' => 1,
            'bonus_minutes' => 0,
            'plant_tree' => 1,
            'bonus_min_exp' => now()->format('Y-m-d'),
            'driving_minutes' => 0,
            'account_balance' => 0,
            'contributions' => 0,
        ]);

        $response = $this->deleteJson("/api/deleteregister/{$user->id}");
        $response = $this->deleteJson("/api/persons/{$person->id}");
        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);

        $this->assertDatabaseMissing('persons', ['id' => $person->id]);
    }
}
