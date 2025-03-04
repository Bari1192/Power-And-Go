<?php

namespace Database\Seeders;

use App\Models\Equipment;

use Illuminate\Database\Seeder;

class EquipmentSeeder  extends Seeder
{
    public function run(): void
    {
        $amount = 5;
        Equipment::factory($amount)->create();
        $this->command->info("\t$amount db felszereltség osztály készült el.");
    }
}
