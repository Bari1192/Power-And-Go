<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $motor_powerek = DB::table('fleets')->distinct()->pluck('motor_power');

        foreach ($motor_powerek as $index => $motor_powerErteke) {
            DB::table('categories')->insert([
                'category_class' => $index + 1,
                'motor_power' => $motor_powerErteke,
            ]);
        }
    }
}
