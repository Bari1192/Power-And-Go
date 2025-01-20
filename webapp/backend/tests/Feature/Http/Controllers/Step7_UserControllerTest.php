<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Person;
use App\Models\User;
use Tests\TestCase;

class Step7_UserControllerTest extends TestCase
{
    public function test_can_get_all_user_data()
    {
        $response = $this->get('/api/users');
        $response->assertStatus(200);

        $data = $response->json('data');
        $response = $this->assertNotEmpty($data);
    }

    public function test_cannot_create_user_without_existing_person()
    {
        $user = [
            "user_name" => fake()->regexify('[A-Za-z]{10}[0-9]{3}'),
            "password" => fake()->regexify('[A-Za-z]{6}[0-9]{2}'),
            "password_2_4" => fake()->regexify('[0-9]{2}'),
            "account_balance" => 0,
            "sub_id" => 1,
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
        $response = $this->postJson('/api/users', [
            'person_id' => $person->id,
            'user_name' => $person['firstname'] . $person['lastname'],
            'password' => $pwd,
            'password_2_4' => $pwd[1] . $pwd[3],
            'account_balance' => 0,
            'sub_id' => 1,
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'person_id' => $person->id,
            'password_2_4' => $pwd[1] . $pwd[3],
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
            "password" => fake()->password(),
            "password_2_4" => $person->person_password[0] . $person->person_password[2],
            "account_balance" => fake()->numberBetween(1000, 100000),
            "sub_id" => 1,
        ]);

        $response = $this->deleteJson("/api/deleteregister/{$user->id}");
        $response = $this->deleteJson("/api/persons/{$person->id}");
        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);

        $this->assertDatabaseMissing('persons', ['id' => $person->id]);
    }
}
