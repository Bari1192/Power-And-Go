<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Auto;
use Illuminate\Support\Facades\DB;

class AutoSeeder extends Seeder
{
    public function run(): void
    {
        $autok = Auto::factory(500)->make()->toArray();
        DB::table('autok')->insert($autok);
    }
}
