<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $persons = Person::whereNotNull('driving_license')
            ->inRandomOrder()
            ->limit(200)
            ->get();

        foreach ($persons as $person) {
            User::factory()->create([
                'person_id' => $person->id,
                'sub_id' => random_int(1, 4),
            ]);
        }
    }
}
