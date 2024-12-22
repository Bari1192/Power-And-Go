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
        $this->call(PriceSeeder::class);
        $this->call(EquipmentSeeder::class);
        $this->call(CarStatusSeeder::class);
        $this->call(CarSeeder::class);
        $this->call(PersonSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(DailyrentalSeeder::class);
        $this->call(CarUserRentsSeeder::class);
        $this->call(BillSeeder::class);
    }
}
