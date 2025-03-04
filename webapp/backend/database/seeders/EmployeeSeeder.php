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
        $amount = 50;
        $persons = Person::inRandomOrder()->limit($amount)->get();
        foreach ($persons as $person) {
            $employee = Employee::factory()->make([
                'person_id' => $person->id,
            ])->toArray();
            DB::table('employees')->insert($employee);
        }
        $this->command->info("\t$amount fő munkavállaló lett létre hozva.");
    }
}
