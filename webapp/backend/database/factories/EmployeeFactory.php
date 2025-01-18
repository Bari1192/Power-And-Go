<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{

    public function definition(): array
    {
        $field = $this->teruletGeneralas();
        $role = $this->munkakorGeneralas($field);
        $position = $this->beosztasGeneralas($role);
        $munkaido = $role === 'Alvállalkozói flottakezelő' ? 'hourly' : 'fix';
        $salary = $this->fizetesGeneralas($position, $munkaido);

        $person = Person::inRandomOrder()->first();

        return [
            'person_id' => $person->id,
            'field' => $field,
            'role' => $role,
            'position' => $position,
            'salary_type' => $munkaido,
            'salary' => $salary,
            'start_date' => fake()->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
        ];
    }
    private function teruletGeneralas(): string
    {
        return fake()->randomElement([
            'Marketing',
            'Adminisztráció',
            'Ügyfélszolgálat',
            'Humánerőforrás',
            'Flottakezelés',
            'IT',
            'Menedzsment',
            'Pénzügy',
            'Jog',
        ]);
    }

    private function munkakorGeneralas(string $field): string
    {
        $munkakorok = [
            'Marketing' => ['Social Media kezelő', 'Social Media Menedzser', 'Kampány-tervező', 'Kampánymenedzser'],
            'Adminisztráció' => ['Foglalásrögzítő', 'Irodai adminisztrátor'],
            'Ügyfélszolgálat' => ['Baleseti-Callcenter', 'Vállalati-Callcenter', 'English-helpdesk', 'Panaszkezelés'],
            'Humánerőforrás' => ['Toborzó', 'HR adminisztrátor'],
            'Flottakezelés' => ['Flottamenedzser', 'Logisztikai ügyintéző', 'Kárbejelentő', 'Alvállalkozói flottakezelő'],
            'IT' => ['Adatbázis Fejlesztő', 'Alkalmazás Fejlesztő', 'Webapplikáció-Fejlesztő', 'Tesztelő', 'Backend-Fejlesztő', 'Rendszermérnök', 'Termékmenedzser'],
            'Menedzsment' => [
                'Projektvezető',
                'Üzletfejlesztési menedzser',
                'HR menedzser',
                'Kommunikációs menedzser',
                'Flottakezelő menedzser',
                'IT menedzser',
            ],
            'Pénzügy' => [
                'Értékesítés',
                'Könyvelés',
                'Vállalati szerződés-kezelő',
                'Bérszámfejtés',
                'Szerződés-kezelés',
            ],
            'Jog' => ['Jogi tanácsadó', 'Ügyvéd'],
        ];

        $munkakorLista = $munkakorok[$field] ?? ['Munkatárs'];

        return fake()->randomElement($munkakorLista);
    }

    private function beosztasGeneralas(string $role): string
    {
        $eloszlas = [
            'Munkatárs' => 60,
            'Supervisor' => 15,
            'Főosztályvezető' => 15,
            'Felsővezető' => 10,
        ];

        $eloszlasMunkatars = $role === 'Alvállalkozói flottakezelő' ? 40 : $eloszlas['Munkatárs'];
        $position = fake()->randomElement(array_merge(
            array_fill(0, $eloszlasMunkatars, 'Munkatárs'),
            array_fill(0, $eloszlas['Supervisor'], 'Supervisor'),
            array_fill(0, $eloszlas['Főosztályvezető'], 'Főosztályvezető'),
            array_fill(0, $eloszlas['Felsővezető'], 'Felsővezető'),
        ));
        return $position;
    }

    private function fizetesGeneralas(string $position, string $munkaido): int
    {
        $berAdatok = [
            'Munkatárs' => ['fix' => 400000, 'hourly' => 2500],
            'Supervisor' => ['fix' => 600000],
            'Főosztályvezető' => ['fix' => 800000],
            'Felsővezető' => ['fix' => 1200000],
        ];

        return $munkaido === 'hourly' ? $berAdatok['Munkatárs']['hourly'] : $berAdatok[$position]['fix'];
    }
}