<?php

namespace Database\Seeders;

use App\Models\LezartBerles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LezartBerlesSeeder extends Seeder
{
    public function run(): void
    {
        $batchSize = 500; // Egyszerre ennyi rekordot szúrunk be
        $totalRecords = 4000;

        // Tömeges beszúrás Factory alapján
        foreach (range(1, $totalRecords / $batchSize) as $batch) {
            $lezartBerlesek = LezartBerles::factory()
                ->count($batchSize)
                ->make()
                ->toArray();

            DB::table('lezart_berlesek')->insert($lezartBerlesek);
        }
    }
}
