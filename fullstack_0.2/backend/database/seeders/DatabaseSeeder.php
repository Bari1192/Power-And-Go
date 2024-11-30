<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(FlottaTipusSeeder::class);
        $this->call(KategoriaSeeder::class);
        $this->call(FelszereltsegSeeder::class);
        $this->call(AutoSeeder::class);
        $this->call(SzemelySeeder::class);
        $this->call(DolgozoSeeder::class);
        $this->call(FelhasznaloSeeder::class);
        $this->call(LezartBerlesSeeder::class);
        $this->call(FutoBerlesSeeder::class);
    }
}
