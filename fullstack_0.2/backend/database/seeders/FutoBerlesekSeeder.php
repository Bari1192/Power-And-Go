<?php

namespace Database\Seeders;

use App\Models\FutoBerles;
use Illuminate\Database\Seeder;

class FutoBerlesekSeeder extends Seeder
{
    public function run(): void
    {
        FutoBerles::factory(100)->create();

    }
}
