<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Person;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{

    public function test_can_regist_user_to_database_with_its_all_keys()
    {
         # Egyedi jelszó a teszthez.
        $person = Person::factory()->make([
            'person_password' => '12345678',
        ])->toArray();

        # 2. Person létrehozása
        $response = $this->post('/api/persons', $person);
        $response->assertStatus(201);
        $getnewUser = Person::latest('id')->first();

        # 3. User létrehozása 
        $response = $this->post('/api/users/', [
            'person_id' => $getnewUser->id,
            'user_name' => 'Test' . fake()->regexify('[0-9]{9}'),
            'password' => Hash::make($person['person_password']),
            'password_2_4' => $person['person_password'][1] . $person['person_password'][3],
            'account_balance' => 0,
            'sub_id' => 1,
        ]);
        dump($response->json()); // Debugging
        $response->assertStatus(201);

        # 3.1 Válasz ellenőrzése
        $userData = $response->json('data');
        $this->assertArrayHasKey('user_name', $userData, 'A válasz nem tartalmazza a `user_name` kulcsot.');

        # 4. Authentikáció
        $response = $this->postJson('/api/authenticate/', [
            "user_name" => $userData['user_name'], 
            "password" => $person['person_password'], # A fenti eredeti jelszó ide
        ]);
        $response->assertStatus(200);
        dump($response->json()); // Debugging

        
        # 5. Token ellenőrzés
        $gotTokenData = $response->json("data");
        $this->assertArrayHasKey('token', $gotTokenData, 'Sikertelen authentikáció. Tokent nem kapott.');
    }
}
