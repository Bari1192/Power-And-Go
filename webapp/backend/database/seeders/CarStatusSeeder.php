<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarStatusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('carstatus')->insert([
            ["status_name" => "Szabad", 'status_descrip' => "Az autó elérhető és bérlésre kész.","created_at"=> now(),"updated_at"=> now()],
            ["status_name" => "Foglalva", 'status_descrip' => "Az autót lefoglalta egy felhasználó.","created_at"=> now(),"updated_at"=> now()],
            ["status_name" => "Bérlés alatt", 'status_descrip' => "Az autót éppen használják.","created_at"=> now(),"updated_at"=> now()],
            ["status_name" => "Baleset miatt kivonva", 'status_descrip' => "Az autóval balesetet okoztak / balesetben vett részt.","created_at"=> now(),"updated_at"=> now()],
            ["status_name" => "Szervízre vár", 'status_descrip' => "Az autó meghibásodott és javításra vár.","created_at"=> now(),"updated_at"=> now()],
            ["status_name" => "Tisztításra vár", 'status_descrip' => "Az autót tisztításra ki kell vonni a forgalomból.","created_at"=> now(),"updated_at"=> now()],
            ["status_name" => "Kritikus töltés", 'status_descrip' => "Az autó akkumulátora rendkívül alacsony szinten van, nem használható.","created_at"=> now(),"updated_at"=> now()],
            ["status_name" => "Előrendelésre lefoglalva", 'status_descrip' => "A jármű előrendelésre és szállításra előzetesen lefoglalva.","created_at"=> now(),"updated_at"=> now()],
        ]);
    }
}
