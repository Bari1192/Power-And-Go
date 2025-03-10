<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class Step11_RegisterControllerTest extends TestCase
{
    use DatabaseTransactions;
    public function test_can_regist_user_to_database_with_its_all_keys()
    {
        $person = [
            "person_password" => Hash::make("12345678"),
            "id_card" => strtoupper(fake()->bothify('??######')),
            "driving_license" => strtoupper(fake()->bothify('??######')),
            "license_start_date" => "2024-01-01",
            "license_end_date" => "2034-01-01",
            "firstname" => "Teszt",
            "lastname" => "Elek",
            "birth_date" => "1990-01-01",
            "phone" => '+36' . 30 . rand(100, 999) . rand(1000, 9999),
            "email" => fake()->bothify('??##########') . "@gmail.com",

        ];
        $response = $this->post('/api/persons', $person);
        $response->assertStatus(201);
        dump($response->json());

        $getnewUser = Person::latest('id')->first();

        $response = $this->post('/api/users/', [
            'person_id' => $getnewUser->id,
            "user_name" => fake()->regexify('[a-zA-Z]{12}'),
            "pin" => Hash::make("9876"),
            "password_2_4" => "24",
            "account_balance" => 0,
            "sub_id" => 1,
            "plant_tree" => true,
            "vip_discount" => true,
            "bonus_min_exp" => "2025-04-30",
            "bonus_minutes" => 0,
            "driving_minutes" => 0,
            "contributions" => 0
        ]);
        dump($response->json());
        $response->assertStatus(201);

        $userData = $response->json('data');
        $this->assertArrayHasKey('user_name', $userData, 'A válasz nem tartalmazza a `user_name` kulcsot.');

        $response = $this->postJson('/api/authenticatelogin/', [
            "password" => "12345678",
            "email" => $person['email'],
        ]);
        $response->assertStatus(200);
        dump($response->json());

        $gotTokenData = $response->json("data");
        $this->assertArrayHasKey('token', $gotTokenData, 'Sikertelen authentikáció. Tokent nem kapott.');
    }
    public function test_user_pin_validated()
    {
        $existingTestUser = User::where('user_name', 'teszteset')->first();
        if (!$existingTestUser) {
            $existingPerson = Person::first();
            if (!$existingPerson) {
                $person = [
                    "person_password" => Hash::make("12345678"),
                    "id_card" => strtoupper(fake()->bothify('??######')),
                    "driving_license" => strtoupper(fake()->bothify('??######')),
                    "license_start_date" => "2024-01-01",
                    "license_end_date" => "2034-01-01",
                    "firstname" => "Teszt",
                    "lastname" => "Elek",
                    "birth_date" => "1990-01-01",
                    "phone" => '+36' . 30 . rand(100, 999) . rand(1000, 9999),
                    "email" => fake()->bothify('??##########') . "@gmail.com",
                ];
                $response = $this->post('/api/persons', $person);
                $existingPerson = Person::latest('id')->first();
            }
            $testUser = [
                'person_id' => $existingPerson->id,
                "user_name" => "teszteset",
                "pin" => "1234",
                "password_2_4" => "24",
                "account_balance" => 0,
                "sub_id" => 1,
                "plant_tree" => true,
                "vip_discount" => true,
                "bonus_min_exp" => "2025-04-30",
                "bonus_minutes" => 0,
                "driving_minutes" => 0,
                "contributions" => 0, 
            ];
            $this->post('/api/users/', $testUser);
            $existingTestUser = User::where('user_name', 'teszteset')->first();
        }
        $testUserResponse = $this->postJson('/api/authenticateuserpin/', [
            "user_name" => "teszteset",
            "pin" => "1234",
        ]);

        dump([
            'Teszteset validációs adatok' => [
                'Felhasználó' => 'teszteset',
                'PIN' => '1234',
                'Válasz' => $testUserResponse->json()
            ]
        ]);
        $username = "autouser" . rand(1000, 9999);
        $pin = "9876";

        $person = [
            "person_password" => Hash::make("12345678"),
            "id_card" => strtoupper(fake()->bothify('??######')),
            "driving_license" => strtoupper(fake()->bothify('??######')),
            "license_start_date" => "2024-01-01",
            "license_end_date" => "2034-01-01",
            "firstname" => "Teszt",
            "lastname" => "Elek",
            "birth_date" => "1990-01-01",
            "phone" => '+36' . 30 . rand(100, 999) . rand(1000, 9999),
            "email" => fake()->bothify('??##########') . "@gmail.com",
        ];
        $response = $this->post('/api/persons', $person);
        $response->assertStatus(201);
        $getnewUser = Person::latest('id')->first();
        $username = "autouser" . rand(1000, 9999);
        $pin = "9876";

        $response = $this->post('/api/users/', [
            'person_id' => $getnewUser->id,
            "user_name" => $username,
            "pin" => $pin,
            "password_2_4" => "24",
            "account_balance" => 0,
            "sub_id" => 1,
            "plant_tree" => true,
            "vip_discount" => true,
            "bonus_min_exp" => "2025-04-30",
            "bonus_minutes" => 0,     // Új mező
            "driving_minutes" => 0,   // Új mező
            "contributions" => 0,     // Új mező
        ]);

        $response->assertStatus(201);
    }
}
