<?php

namespace Database\Seeders;

use App\Models\Szemely;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class SzemelyekSeeder extends Seeder
{
    public function run(): void
    {
        Szemely::factory(300)->create();
    }
}
