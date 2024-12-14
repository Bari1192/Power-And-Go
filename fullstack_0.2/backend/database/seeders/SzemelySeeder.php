<?php

namespace Database\Seeders;

use App\Models\Szemely;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class SzemelySeeder extends Seeder
{
    public function run(): void
    {
        Szemely::factory(1500)->create();
    }
}
