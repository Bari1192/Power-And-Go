<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Auto; 

class AutokSeeder extends Seeder
{
    public function run(): void
    {
        Auto::factory(250)->create();
    }
}
