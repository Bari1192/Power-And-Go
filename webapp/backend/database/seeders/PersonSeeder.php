<?php

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();

        $totalPersons = 400;
        $batchSize = 100;

        try {
            DB::disableQueryLog();
            for ($i = 0; $i < $totalPersons; $i += $batchSize) {
                $currentBatchSize = min($batchSize, $totalPersons - $i);
                $persons = Person::factory()
                    ->count($currentBatchSize)
                    ->make()
                    ->toArray();
                DB::table('persons')->insert($persons);
            }
            DB::commit();

            $this->command->info("\tSzemélyek sikeresen létrehozva: $totalPersons fő.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Hiba történt: " . $e->getMessage());
            throw $e;
        }
    }
}
