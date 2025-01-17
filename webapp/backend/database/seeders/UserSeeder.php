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
                            ->whereNotNull('license_start_date')
                            ->whereNotNull('license_end_date')
                            ->inRandomOrder()
                            ->limit(100)
                            ->get();

        foreach ($persons as $szemely) {
            $randomElofizetes = Subscription::inRandomOrder()->first();

            User::factory()->create([
                'person_id' => $szemely->id,
                'sub_id' => $randomElofizetes->id, // Az előfizetés ID-t kapcsoljuk
            ]);
        }
    }
}
