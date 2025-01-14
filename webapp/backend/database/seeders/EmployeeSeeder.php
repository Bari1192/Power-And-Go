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
        $szemelyek = Person::inRandomOrder()->limit(300)->get();

        foreach ($szemelyek as $szemely) {
            // Létrehozzuk a dolgozó adatait factory segítségével
            $dolgozo = Employee::factory()->make([
                'szemely_azon' => $szemely->id,
            ])->toArray();

            // Beszúrás az adatbázisba
            DB::table('employees')->insert($dolgozo);
        }
    }
}
