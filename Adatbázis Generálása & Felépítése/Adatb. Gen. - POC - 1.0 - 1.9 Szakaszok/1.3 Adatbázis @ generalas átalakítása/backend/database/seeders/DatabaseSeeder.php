<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(FlottaTipusokSeeder::class);
        $this->call(KategoriakSeeder::class);
        $this->call(FelszereltsegekSeeder::class); 
        $this->call(AutokSeeder::class);
        $this->call(SzemelyekSeeder::class);
        $this->call(DolgozoSeeder::class);
        $this->call(FelhasznalokSeeder::class);
        $this->call(LezartBerlesekSeeder::class);
        $this->call(FutoBerlesekSeeder::class);
    }
}
