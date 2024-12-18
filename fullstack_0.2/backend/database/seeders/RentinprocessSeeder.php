<?php

namespace Database\Seeders;

use App\Models\Rentinprocess;
use Illuminate\Database\Seeder;

class RentinprocessSeeder extends Seeder
{
    public function run(): void
    {
        Rentinprocess::factory(100)->create();
    }
}
