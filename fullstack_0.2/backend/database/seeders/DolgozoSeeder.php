<?php

namespace Database\Seeders;

use App\Models\Dolgozo;
use App\Models\Person;
use App\Models\Szemely;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DolgozoSeeder extends Seeder
{
    public function run(): void
    {
        $szemelyek = Person::inRandomOrder()->limit(300)->get();

        foreach ($szemelyek as $szemely) {
            // Létrehozzuk a dolgozó adatait factory segítségével
            $dolgozo = Dolgozo::factory()->make([
                'szemely_id_fk' => $szemely->id,
            ])->toArray();

            // Beszúrás az adatbázisba
            DB::table('dolgozok')->insert($dolgozo);
        }
    }
}
