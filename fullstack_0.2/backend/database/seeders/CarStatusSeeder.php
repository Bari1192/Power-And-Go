<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('carstatus')->insert([
            ["status_name" => "Szabad", 'status_descrip' => "Az autó elérhető és bérlésre kész."],
            ["status_name" => "Foglalva", 'status_descrip' => "Az autót lefoglalta egy felhasználó."],
            ["status_name" => "Bérlés alatt", 'status_descrip' => "Az autót éppen használják."],
            ["status_name" => "Szervízre vár", 'status_descrip' => "Az autó meghibásodott és javításra vár."],
            ["status_name" => "Tisztításra vár", 'status_descrip' => "Az autót tisztításra ki kell vonni a forgalomból."],
            ["status_name" => "Kritikus töltés", 'status_descrip' => "Az autó akkumulátora rendkívül alacsony szinten van, nem használható."],
        ]);
    }
}
