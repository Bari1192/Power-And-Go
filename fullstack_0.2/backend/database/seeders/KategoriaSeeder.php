<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $teljesitmenyek = DB::table('flotta_tipusok')->distinct()->pluck('teljesitmeny');

        foreach ($teljesitmenyek as $index => $teljesitmenyErteke) {
            DB::table('kategoriak')->insert([
                'kat_besorolas' => $index + 1, // Az indexet használjuk kategória besorolásként
                'teljesitmeny' => $teljesitmenyErteke,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
