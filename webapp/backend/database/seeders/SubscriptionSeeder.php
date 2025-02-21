<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('subscriptions')->insert([

            [
                'sub_name' => 'Power',
                'sub_monthly' => null,
                'sub_annual' => null,
            ],
            [
                'sub_name' => 'Power-Plus',
                'sub_monthly' => 490,
                'sub_annual' => null,
            ],
            [
                'sub_name' => 'Power-Premium',
                'sub_monthly' => 1690,
                'sub_annual' => null,
            ],
            [
                'sub_name' => 'Power-VIP',
                'sub_monthly' => 5990,
                'sub_annual' => 59900,
            ],
        ]);
    }
}
