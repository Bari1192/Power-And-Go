<?php

namespace Database\Seeders;

use App\Models\FutoBerles;
use Illuminate\Database\Seeder;

class FutoBerlesSeeder extends Seeder
{
    public function run(): void
    {
        FutoBerles::factory(100)->create();
    }
}
