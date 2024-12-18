<?php

namespace Database\Seeders;

use App\Models\Felhasznalo;
use App\Models\Person;
use App\Models\Subscription;
use Illuminate\Database\Seeder;

class FelhasznaloSeeder extends Seeder
{
    public function run(): void
    {
        $szemelyek = Person::whereNotNull('jogos_szam')
                            ->whereNotNull('jogos_erv_kezdete')
                            ->whereNotNull('jogos_erv_vege')
                            ->inRandomOrder()
                            ->limit(100)
                            ->get();

        foreach ($szemelyek as $szemely) {
            $randomElofizetes = Subscription::inRandomOrder()->first();

            Felhasznalo::factory()->create([
                'szemely_id' => $szemely->id,
                'elofiz_id' => $randomElofizetes->id, // Az előfizetés ID-t kapcsoljuk
            ]);
        }
    }
}
