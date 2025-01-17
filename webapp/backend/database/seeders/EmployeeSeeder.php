<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $persons = Person::inRandomOrder()->limit(300)->get();

        foreach ($persons as $person) {
            // Létrehozzuk a dolgozó adatait factory segítségével
            $dolgozo = Employee::factory()->make([
                'person_id' => $person->id,
            ])->toArray();

            // Beszúrás az adatbázisba
            DB::table('employees')->insert($dolgozo);
        }
    }
}
