<?php

namespace Database\Seeders;

use App\Models\LezartBerles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LezartBerlesSeeder extends Seeder
{
    public function run(): void
    {
        LezartBerles::factory(4000)->create();
    }
}
