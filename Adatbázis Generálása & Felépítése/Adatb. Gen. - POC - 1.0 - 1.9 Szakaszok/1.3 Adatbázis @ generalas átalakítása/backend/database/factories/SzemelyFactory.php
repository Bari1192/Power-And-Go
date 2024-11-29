<?php

namespace Database\Factories;

use App\Models\Szemely;
use Illuminate\Database\Eloquent\Factories\Factory;

class SzemelyFactory extends Factory
{
    public function definition(): array
    {

        $szulDatum = fake()->dateTimeBetween('-64 years', '-18 years');
        $jogsiKezdete = fake()->dateTimeBetween('-10 years', 'now');
        $jogsiVege = (clone $jogsiKezdete)->modify('+10 years');


        return [
            'v_nev' => fake()->lastName(),
            'k_nev' => fake()->firstName(),
            'szul_datum' => $szulDatum->format('Y-m-d'),
            'telefon' => fake()->regexify('\+36(20|30|70)[0-9]{3}[0-9]{4}'),
            'email' => fake()->unique()->safeEmail(),
            'szig_szam' => $this->egyediSzigSzam(),
            'jogos_szam' => $this->egyediJogosSzam(),
            'jogos_erv_kezdete' => $jogsiKezdete->format('Y-m-d'),
            'jogos_erv_vege' => $jogsiVege->format('Y-m-d'),
            'szemely_jelszo' => str_pad((string) fake()->numberBetween(1000, 9999), 4, '0', STR_PAD_LEFT), // Helyesen generált jelszó
        ];
    }
    private function egyediSzigSzam(): string
    {
        do {
            $szigSzam = fake()->unique()->bothify('??######'); 
        } while (Szemely::where('szig_szam', $szigSzam)->exists());

        return $szigSzam;
    }

    private function egyediJogosSzam(): string
    {
        do {
            $jogosSzam = fake()->unique()->bothify('??######'); 
        } while (Szemely::where('jogos_szam', $jogosSzam)->exists());

        return $jogosSzam;
    }
}
