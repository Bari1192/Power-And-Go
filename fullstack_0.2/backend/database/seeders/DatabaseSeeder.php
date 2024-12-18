<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(FleetSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(SubscriptionSeeder::class);
        $this->call(ArazasSeeder::class);
        $this->call(EquipmentSeeder::class);
        $this->call(CarStatusSeeder::class);
        $this->call(CarSeeder::class);
        $this->call(PersonSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(NapiBerlesSeeder::class);
        $this->call(RenthistorySeeder::class);
        $this->call(FutoBerlesSeeder::class);
        $this->call(SzamlaSeeder::class);
    }
}
