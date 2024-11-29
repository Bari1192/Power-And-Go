<?php

namespace Database\Seeders;

use App\Models\Felhasznalo;
use App\Models\Szemely;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FelhasznalokSeeder extends Seeder
{
    public function run(): void
    {
        $szemelyek = Szemely::inRandomOrder()->limit(100)->get();
        foreach ($szemelyek as $szemely) {
            Felhasznalo::factory()->create([
                'szemely_id' => $szemely->szemely_id,
            ]);
        }
    }
}