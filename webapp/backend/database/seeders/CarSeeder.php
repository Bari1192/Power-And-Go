<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;
use App\Models\Fleet;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $totalCars = 600;
        $chunkSize = 100;

        ## Garantáltan legalább 2-3 db [2,4,5] kategóriájú autó létrejöjjön.
        $guaranteedCategoryClass2 = rand(2, 3);
        $guaranteedCategoryClass4 = rand(2, 3);
        $guaranteedCategoryClass5 = rand(2, 3);

        ## Ezeket a db-ket levonni a totálból:
        $remainingCars = $totalCars - ($guaranteedCategoryClass2 + $guaranteedCategoryClass4 + $guaranteedCategoryClass5);

        // 40% (3-as), 60% (1-es) eloszlás a fennmaradó autókra
        $guaranteedCategoryClass3 = (int)($remainingCars * 0.4);
        $guaranteedCategoryClass1 = $remainingCars - $guaranteedCategoryClass3;

        try {
            DB::beginTransaction();
            $this->generateSpecificCategoryCars($guaranteedCategoryClass2, 2);
            $this->generateSpecificCategoryCars($guaranteedCategoryClass4, 4);
            $this->generateSpecificCategoryCars($guaranteedCategoryClass5, 5);

            for ($i = 0; $i < $guaranteedCategoryClass1; $i += $chunkSize) {
                $currentBatchSize = min($chunkSize, $guaranteedCategoryClass1 - $i);
                $this->generateSpecificCategoryCars($currentBatchSize, 1);
            }

            for ($i = 0; $i < $guaranteedCategoryClass3; $i += $chunkSize) {
                $currentBatchSize = min($chunkSize, $guaranteedCategoryClass3 - $i);
                $this->generateSpecificCategoryCars($currentBatchSize, 3);
            }

            DB::commit();
            $this->logCategoryDistribution();

            $this->command->info("\tÖsszesen $totalCars db autó van az adatbázisban.");
        } catch (Exception $e) {
            DB::rollBack();
            echo "Hiba történt: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    private function generateSpecificCategoryCars(int $count, int $categoryId): void
    {
        if ($count <= 0) {
            return;
        }
        $motorPower = match ($categoryId) {
            1 => 18,
            2 => 33,
            3 => 36,
            4 => 65,
            5 => 75,
            default => 18,
        };
        $fleet = Fleet::where('motor_power', $motorPower)->inRandomOrder()->first();
        if (!$fleet) {
            Log::warning("Nem található flotta a következő motor_power értékkel: $motorPower");
            return;
        }
        $cars = Car::factory()->count($count)->make([
            'fleet_id' => $fleet->id,
            'category_id' => $categoryId,
        ])->toArray();

        DB::table('cars')->insert($cars);
    }
    private function logCategoryDistribution(): void
    {
        $categoryStats = DB::table('cars')
            ->select('category_id', DB::raw('count(*) as count'))
            ->groupBy('category_id')
            ->orderBy('category_id')
            ->get();
        foreach ($categoryStats as $stat) {
            $this->command->info("\t{$stat->category_id}. kategóriából {$stat->count} db autó sikeresen létrehozva.");
        }
    }
}
