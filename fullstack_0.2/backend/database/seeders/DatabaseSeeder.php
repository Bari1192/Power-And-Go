<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(FleetSeeder::class);
        $this->call(KategoriaSeeder::class);
        $this->call(ElofizetesSeeder::class);
        $this->call(ArazasSeeder::class);
        $this->call(FelszereltsegSeeder::class);
        $this->call(CarStatusSeeder::class);
        $this->call(CarSeeder::class);
        $this->call(SzemelySeeder::class);
        $this->call(DolgozoSeeder::class);
        $this->call(FelhasznaloSeeder::class);
        $this->call(NapiBerlesSeeder::class);
        $this->call(LezartBerlesSeeder::class);
        $this->call(FutoBerlesSeeder::class);
        $this->call(SzamlaSeeder::class);
    }
}
