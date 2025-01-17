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

        $firstname = fake()->lastName();
        $lastname = fake()->firstName();

        return [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'birth_date' => $szulDatum->format('Y-m-d'),
            'phone' => fake()->regexify('\+36(20|30|70)[0-9]{3}[0-9]{4}'),
            'email' => $this->emailGeneralas($firstname, $lastname),
            'id_card' => strtoupper($this->egyediSzigSzam()),
            'driving_license' => strtoupper($this->egyediJogosSzam()),
            'license_start_date' => $jogsiKezdete->format('Y-m-d'),
            'license_end_date' => $jogsiVege->format('Y-m-d'),
            'person_password' => fake()->regexify('\[0-9]{8}'),
        ];
    }
    private function egyediSzigSzam(): string
    {
        do {
            $szigSzam = fake()->unique()->bothify('??######');
        } while (Person::where('id_card', $szigSzam)->exists());

        return $szigSzam;
    }

    private function egyediJogosSzam(): string
    {
        do {
            $jogosSzam = fake()->unique()->bothify('??######');
        } while (Person::where('driving_license', $jogosSzam)->exists());

        return $jogosSzam;
    }

    public function emailGeneralas($firstname, $lastname): string
    {
        $domainek = ['@gmail.com', '@yahoo.com', '@outlook.com'];

        // Ékezetek eltávolítása és kisbetűs átalakítás
        $firstname = strtolower($this->removeAccents($firstname));
        $lastname = strtolower($this->removeAccents($lastname));

        do {
            $carmodelValaszto = random_int(1, 3);

            // [rand.Szám + vnev + domain]
            if ($carmodelValaszto === 1) {
                $szamok = random_int(100, 999);
                $email = $firstname . $szamok . $domainek[array_rand($domainek)];
            }
            // [vnev + knev + domain]
            elseif ($carmodelValaszto === 2) {
                $szamok = random_int(100, 999);
                $email = $firstname . $lastname . $szamok . $domainek[array_rand($domainek)];
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
