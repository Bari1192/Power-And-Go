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
        $persons = Person::whereNotNull('jogos_szam')
                            ->whereNotNull('jogos_erv_kezdete')
                            ->whereNotNull('jogos_erv_vege')
                            ->inRandomOrder()
                            ->limit(100)
                            ->get();

        foreach ($persons as $szemely) {
            $randomElofizetes = Subscription::inRandomOrder()->first();

            User::factory()->create([
                'person_id' => $szemely->id,
                'elofiz_id' => $randomElofizetes->id, // Az előfizetés ID-t kapcsoljuk
            ]);
        }
    }
}
