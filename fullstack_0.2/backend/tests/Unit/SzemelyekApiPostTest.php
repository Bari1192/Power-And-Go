<?php

namespace Tests\Unit;

use Tests\TestCase;         ## Ez kell, mert különben a postJson nem fog menni!
use RefreshDatabase; // Automatikusan tiszta adatbázis minden teszt előtt


class SzemelyekApiPostTest extends TestCase
{

    public function test_szemelyek_Api_Post_Test_tablaba(): void
    {
        # Adatok betöltése / megadása, amit felküldünk majd.
        $payload = [
            "szemely_jelszo" => "1234",
            "szig_szam" => "ABC123456",
            "jogos_szam" => "XYZ789123",
            "jogos_erv_kezdete" => "2023-01-01",
            "jogos_erv_vege" => "2025-12-31",
            "v_nev" => "Kiss",
            "k_nev" => "János",
            "szul_datum" => "1990-06-15",
            "telefon" => "+36123456789",
            "email" => "kiss.janos@example.com",
        ];

        ## Végpont, ahová az adatokat küldjük
        $response = $this->postJson('/api/szemelyek', $payload);

        ### Amit vissza szeretnénk kapni (201 -> sikeres "beküldés")
        #### JSON - Data -ban pedig az adat, ami "bemenjen"
        $response
            ->assertStatus(201) // Várjuk a sikeres státuszkódot
            ->assertJson([
                'data' => [
                    "szemely_jelszo" => "1234",
                    "szig_szam" => "ABC123456",
                    "jogos_szam" => "XYZ789123",
                    "jogos_erv_kezdete" => "2023-01-01",
                    "jogos_erv_vege" => "2025-12-31",
                    "v_nev" => "Kiss",
                    "k_nev" => "János",
                    "szul_datum" => "1990-06-15",
                    "telefon" => "+36123456789",
                    "email" => "kiss.janos@example.com",
                ],
            ]);

        ### Ellenőrzés, hogy az adat bekerült az adatbázisba
        $this->assertDatabaseHas('szemelyek', [
            "szemely_jelszo" => "1234",
            "szig_szam" => "ABC123456",
            "jogos_szam" => "XYZ789123",
            "v_nev" => "Kiss",
            "k_nev" => "János",
            "szul_datum" => "1990-06-15",
            "telefon" => "+36123456789",
            "email" => "kiss.janos@example.com",
        ]);
    }
}
