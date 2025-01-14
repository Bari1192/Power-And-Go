<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $teljesitmenyek = DB::table('fleets')->distinct()->pluck('teljesitmeny');

        foreach ($teljesitmenyek as $index => $teljesitmenyErteke) {
            DB::table('categories')->insert([
                'kat_besorolas' => $index + 1, // Az indexet használjuk kategória besorolásként
                'teljesitmeny' => $teljesitmenyErteke,
            ]);
        }
    }
}
