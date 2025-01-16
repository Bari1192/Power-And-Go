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
            'szemely_jelszo' => '12345678',
        ])->toArray();

        # 2. Person létrehozása
        $response = $this->post('/api/persons', $person);
        $response->assertStatus(201);
        $getnewUser = Person::latest('id')->first();

        # 3. User létrehozása 
        $response = $this->post('/api/users/', [
            'person_id' => $getnewUser->id,
            'user_name' => 'Test' . fake()->regexify('[0-9]{9}'),
            'password' => Hash::make($person['szemely_jelszo']),
            'jelszo_2_4' => $person['szemely_jelszo'][0] . $person['szemely_jelszo'][2],
            'felh_egyenleg' => 0,
            'elofiz_id' => 1,
        ]);
        dump($response->json()); // Debugging
        $response->assertStatus(201);

        # 3.1 Válasz ellenőrzése
        $userData = $response->json('data');
        $this->assertArrayHasKey('user_name', $userData, 'A válasz nem tartalmazza a `user_name` kulcsot.');

        # 4. Authentikáció
        $response = $this->postJson('/api/authenticate/', [
            "user_name" => $userData['user_name'], 
            "password" => $person['szemely_jelszo'], # A fenti eredeti jelszó ide
        ]);
        $response->assertStatus(200);
        dump($response->json()); // Debugging

        
        # 5. Token ellenőrzés
        $gotTokenData = $response->json("data");
        $this->assertArrayHasKey('token', $gotTokenData, 'Sikertelen authentikáció. Tokent nem kapott.');
    }
}
