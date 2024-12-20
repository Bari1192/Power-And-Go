<?php

namespace Database\Seeders;

use App\Models\Renthistory;
use App\Models\Rentinprocess;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RenthistorySeeder extends Seeder
{
    public function run(): void
    {
        $lezart = Renthistory::factory(50)->make()->toArray();
        DB::table('renthistories')->insert($lezart); 
     
    }
}
