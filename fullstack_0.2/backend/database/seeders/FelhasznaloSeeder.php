<?php

namespace Database\Seeders;

use App\Models\Elofizetes;
use App\Models\Felhasznalo;
use App\Models\Szemely;
use Illuminate\Database\Seeder;

class FelhasznaloSeeder extends Seeder
{
    public function run(): void
    {
        $szemelyek = Szemely::whereNotNull('jogos_szam')
                            ->whereNotNull('jogos_erv_kezdete')
                            ->whereNotNull('jogos_erv_vege')
                            ->inRandomOrder()
                            ->limit(100)
                            ->get();

        foreach ($szemelyek as $szemely) {
            $randomElofizetes = Elofizetes::inRandomOrder()->first();

            Felhasznalo::factory()->create([
                'szemely_id' => $szemely->szemely_id,
                'elofiz_id' => $randomElofizetes->elofiz_id, // Az előfizetés ID-t kapcsoljuk
            ]);
        }
    }
}
