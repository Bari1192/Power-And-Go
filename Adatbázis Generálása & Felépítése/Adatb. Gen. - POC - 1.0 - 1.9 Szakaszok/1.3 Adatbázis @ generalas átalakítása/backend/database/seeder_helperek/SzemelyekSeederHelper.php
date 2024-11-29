<?php

namespace Database\SeederHelperek;
use Faker\Factory as Faker;

class SzemelyekSeederHelper
{

    private array $legeneraltSzemelyik = [];

    public function generateNev(): array
    {
        $faker = Faker::create('hu_HU');
        return [
            'v_nev' => $faker->lastName(),
            'k_nev' => $faker->firstName(),
            'szul_datum' => $faker->dateTimeBetween('-64 years', '-18 years'),
        ];
    }
    public function generateTelefon(): string
    {
        $faker = Faker::create('hu_HU');
        return $faker->regexify('\+36(20|30|70)[0-9]{3}[0-9]{4}');
    }

    public function generateEmail(): string
    {
        $faker = Faker::create();
        return $faker->safeEmail();
    }

    public function generateSzemelyiIgazolvany(): string
    {
        $faker = Faker::create();
        do {
            $betuk = strtoupper($faker->bothify('??'));
            $szamok = str_pad((string) $faker->numberBetween(0, 999999), 6, '0', STR_PAD_LEFT);
            $elkeszult = $betuk . $szamok;
        } while (in_array($elkeszult, $this->legeneraltSzemelyik));
        $this->legeneraltSzemelyik[] = $elkeszult;

        return $elkeszult;
    }

    public function generateJogsiDatumok(): array
    {
        $faker = Faker::create();
        $kezdete = $faker->dateTimeBetween('-10 years', 'now');
        $vege = (clone $kezdete)->modify('+10 years');

        return [
            'jogos_erv_kezdete' => $kezdete,
            'jogos_erv_vege' => $vege,
        ];
    }

    public function generateFelhJelszo(): string
    {
        $faker = Faker::create();
        return str_pad((string)$faker->numberBetween(1000, 9999), 4, '0', STR_PAD_LEFT);
        #0123 esetében '123' lenne, ezért STRING kell, hogy maradjon!
    }
}