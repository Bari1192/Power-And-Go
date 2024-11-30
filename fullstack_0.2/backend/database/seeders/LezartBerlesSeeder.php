<?php

namespace Database\Seeders;

use App\Models\LezartBerles;
use Illuminate\Database\Seeder;

class LezartBerlesSeeder extends Seeder
{
    public function run(): void
    {
        LezartBerles::factory(100)->create();
    }
}
