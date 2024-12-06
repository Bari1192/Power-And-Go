<?php

namespace Tests\Unit;

use Tests\TestCase;         ## Ez kell, mert különben a postJson nem fog menni!
use RefreshDatabase;

class AutokApiPostTest extends TestCase
{
    public function test_autok_Api_Post_Test_tablaba(): void
    {
        # Adatok betöltése / megadása amit felküldünk majd.
        $payload = [
            "rendszam" => "XYZ-888",
            "kategoria_besorolas_fk" => 1,
            "felsz_id_fk" => 2,
            "flotta_id_fk" => 3,
            "km_ora_allas" => 25000,
            "gyartasi_ev" => 2023,
        ];

        ## Végpont, ahová az adatokat küldjük
        $response = $this->postJson('/api/autok', $payload);

        ### Amit vissza szeretnénk kapni (201 -> sikeres "beküldés")
        #### JSON - Data -ban, pedig AZ ADAT, ami "bemenjen"
        $response
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    "rendszam" => "XYZ-888",
                    'kategoria_besorolas_fk' => 1,
                    'felsz_id_fk' => 2,
                    'flotta_id_fk' => 3,
                    'km_ora_allas' => 25000,
                    'gyartasi_ev' => 2023,
                ],
            ]);
    }
}

### FUTÁS ==> php artisan test ###