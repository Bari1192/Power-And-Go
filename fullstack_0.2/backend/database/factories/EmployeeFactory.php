<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{

    public function definition(): array
    {
        $terulet = $this->teruletGeneralas();
        $munkakor = $this->munkakorGeneralas($terulet);
        $beosztas = $this->beosztasGeneralas($munkakor);
        $munkaido = $munkakor === 'Alvállalkozói flottakezelő' ? 'oradij' : 'fix';
        $fizetes = $this->fizetesGeneralas($beosztas, $munkaido);

        $szemely = Person::inRandomOrder()->first();

        return [
            'szemely_azon' => $szemely->id,
            'terulet' => $terulet,
            'munkakor' => $munkakor,
            'beosztas' => $beosztas,
            'munkaido' => $munkaido,
            'fizetes_ossz' => $fizetes,
            'belepes_datum' => fake()->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
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

    private function munkakorGeneralas(string $terulet): string
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

        $munkakorLista = $munkakorok[$terulet] ?? ['Munkatárs'];

        return fake()->randomElement($munkakorLista);
    }

    private function beosztasGeneralas(string $munkakor): string
    {
        $eloszlas = [
            'Munkatárs' => 60,
            'Supervisor' => 15,
            'Főosztályvezető' => 15,
            'Felsővezető' => 10,
        ];

        $eloszlasMunkatars = $munkakor === 'Alvállalkozói flottakezelő' ? 40 : $eloszlas['Munkatárs'];
        $beosztas = fake()->randomElement(array_merge(
            array_fill(0, $eloszlasMunkatars, 'Munkatárs'),
            array_fill(0, $eloszlas['Supervisor'], 'Supervisor'),
            array_fill(0, $eloszlas['Főosztályvezető'], 'Főosztályvezető'),
            array_fill(0, $eloszlas['Felsővezető'], 'Felsővezető'),
        ));
        return $beosztas;
    }

    private function fizetesGeneralas(string $beosztas, string $munkaido): int
    {
        $berAdatok = [
            'Munkatárs' => ['fix' => 400000, 'oradij' => 2500],
            'Supervisor' => ['fix' => 600000],
            'Főosztályvezető' => ['fix' => 800000],
            'Felsővezető' => ['fix' => 1200000],
        ];

        return $munkaido === 'oradij' ? $berAdatok['Munkatárs']['oradij'] : $berAdatok[$beosztas]['fix'];
    }
}