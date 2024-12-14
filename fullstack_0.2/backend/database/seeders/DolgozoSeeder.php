<?php

namespace Database\Seeders;

use App\Models\Dolgozo;
use App\Models\Szemely;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DolgozoSeeder extends Seeder
{
    public function run(): void
    {
        $szemelyek = Szemely::inRandomOrder()->limit(300)->get();

        foreach ($szemelyek as $szemely) {
            Dolgozo::factory()->create([
                'szemely_id_fk' => $szemely->szemely_id,
            ]);
        }
    }
}
