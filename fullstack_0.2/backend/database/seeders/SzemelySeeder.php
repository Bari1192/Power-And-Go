<?php

namespace Database\Seeders;

use App\Models\Szemely;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SzemelySeeder extends Seeder
{
    public function run(): void
    {
        // Szemely::factory(1500)->create();
        $szemely = Szemely::factory(500)->make()->toArray();
        DB::table('szemelyek')->insert($szemely); 
    }
}
