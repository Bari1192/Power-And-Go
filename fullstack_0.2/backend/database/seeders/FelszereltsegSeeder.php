<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Felszereltseg;

use Illuminate\Database\Seeder;

class FelszereltsegSeeder  extends Seeder
{
    public function run(): void
    {
        Felszereltseg::factory(5)->create();
    }
}
