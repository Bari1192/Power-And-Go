<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriakSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategoriak')->insert([
            ['kat_besorolas' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['kat_besorolas' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['kat_besorolas' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['kat_besorolas' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['kat_besorolas' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
