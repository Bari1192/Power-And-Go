<?php

namespace Database\Seeders;

use App\Models\LezartBerles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LezartBerlesSeeder extends Seeder
{
    public function run(): void
    {
        $lezart = LezartBerles::factory(100)->make()->toArray();
        DB::table('lezart_berlesek')->insert($lezart); 
    }
}
