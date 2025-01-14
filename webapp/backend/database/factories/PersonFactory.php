<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    public function definition(): array
    {

        $szulDatum = fake()->dateTimeBetween('-64 years', '-18 years');
        $jogsiKezdete = fake()->dateTimeBetween('-10 years', 'now');
        $jogsiVege = (clone $jogsiKezdete)->modify('+10 years');

        $v_nev = fake()->lastName();
        $k_nev = fake()->firstName();

        return [
            'v_nev' => $v_nev,
            'k_nev' => $k_nev,
            'szul_datum' => $szulDatum->format('Y-m-d'),
            'telefon' => fake()->regexify('\+36(20|30|70)[0-9]{3}[0-9]{4}'),
            'email' => $this->emailGeneralas($v_nev, $k_nev),
            'szig_szam' => strtoupper($this->egyediSzigSzam()),
            'jogos_szam' => strtoupper($this->egyediJogosSzam()),
            'jogos_erv_kezdete' => $jogsiKezdete->format('Y-m-d'),
            'jogos_erv_vege' => $jogsiVege->format('Y-m-d'),
            'szemely_jelszo' => fake()->regexify('\[0-9]{4}'),
        ];
    }
    private function egyediSzigSzam(): string
    {
        do {
            $szigSzam = fake()->unique()->bothify('??######');
        } while (Person::where('szig_szam', $szigSzam)->exists());

        return $szigSzam;
    }

    private function egyediJogosSzam(): string
    {
        do {
            $jogosSzam = fake()->unique()->bothify('??######');
        } while (Person::where('jogos_szam', $jogosSzam)->exists());

        return $jogosSzam;
    }

    public function emailGeneralas($v_nev, $k_nev): string
    {
        $domainek = ['@gmail.com', '@yahoo.com', '@outlook.com'];

        // Ékezetek eltávolítása és kisbetűs átalakítás
        $v_nev = strtolower($this->removeAccents($v_nev));
        $k_nev = strtolower($this->removeAccents($k_nev));

        do {
            $tipusValaszto = random_int(1, 3);

            // [rand.Szám + vnev + domain]
            if ($tipusValaszto === 1) {
                $szamok = random_int(100, 999);
                $email = $v_nev . $szamok . $domainek[array_rand($domainek)];
            }
            // [vnev + knev + domain]
            elseif ($tipusValaszto === 2) {
                $szamok = random_int(100, 999);
                $email = $v_nev . $k_nev . $szamok . $domainek[array_rand($domainek)];
            }
            // [random szó + szám + domain]
            else {
                $randomszo = fake()->word;
                $szamok = random_int(1000, 9999);
                $email = $randomszo . $szamok . $domainek[array_rand($domainek)];
            }
            $email = preg_replace('/[^\x20-\x7E]/', '', $email);
        } while (Person::where('email', $email)->exists());

        return $email;
    }
    private function removeAccents(string $string): string
    {
        $search = ['á', 'é', 'í', 'ó', 'ö', 'ő', 'ú', 'ü', 'ű', 'Á', 'É', 'Í', 'Ó', 'Ö', 'Ő', 'Ú', 'Ü', 'Ű'];
        $replace = ['a', 'e', 'i', 'o', 'o', 'o', 'u', 'u', 'u', 'A', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'U'];
        return str_replace($search, $replace, $string);
    }
}
