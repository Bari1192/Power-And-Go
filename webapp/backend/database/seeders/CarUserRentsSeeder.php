<?php

namespace Database\Seeders;

use App\Models\Renthistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class CarUserRentsSeeder extends Seeder
{
    // Előre feltöltött gyorsítótárak
    private array $parkingBatch = [];
    private array $chargingBatch = [];
    private int $batchParkingCount = 0;
    private int $batchChargingCount = 0;
    private const BATCH_INSERT_SIZE = 200;

    // Haladásjelző objektum
    private ?ProgressBar $progressBar = null;
    private ?ConsoleOutput $output = null;

    public function __construct()
    {
        $this->output = new ConsoleOutput();
    }

    private function bulkSaveParkings(): void
    {
        if (!empty($this->parkingBatch)) {
            DB::table('car_user_rent_parkings')->insert($this->parkingBatch);
            $this->parkingBatch = [];
            $this->batchParkingCount = 0;
        }
    }

    private function bulkSaveChargings(): void
    {
        if (!empty($this->chargingBatch)) {
            DB::table('car_user_rent_charges')->insert($this->chargingBatch);
            $this->chargingBatch = [];
            $this->batchChargingCount = 0;
        }
    }

    private function addParkingToBatch(int $rentId, array $parkolas): void
    {
        $this->parkingBatch[] = [
            'rent_id'         => $rentId,
            'parking_start'   => $parkolas['kezd'],
            'parking_end'     => $parkolas['veg'],
            'parking_minutes' => $parkolas['parking_minutes'],
            'parking_cost'    => $parkolas['total_cost'],
        ];

        $this->batchParkingCount++;

        // Ha elérjük a batch méretet, akkor végrehajtjuk a beszúrást
        if ($this->batchParkingCount >= self::BATCH_INSERT_SIZE) {
            $this->bulkSaveParkings();
        }
    }

    private function addChargingToBatch(int $rentId, array $charging): void
    {
        // Carbon objektumok előzetes formázása string-ekké
        $startDateStr = is_string($charging['charging_start_date'])
            ? $charging['charging_start_date']
            : $charging['charging_start_date']->format('Y-m-d H:i:s');

        $endDateStr = is_string($charging['charging_end_date'])
            ? $charging['charging_end_date']
            : $charging['charging_end_date']->format('Y-m-d H:i:s');

        $this->chargingBatch[] = [
            'rent_id'             => $rentId,
            'charging_start_date' => $startDateStr,
            'charging_end_date'   => $endDateStr,
            'charging_time'       => $charging['charging_time'],
            'start_percent'       => $charging['start_percent'],
            'end_percent'         => $charging['end_percent'],
            'start_kw'            => $charging['start_kw'],
            'end_kw'              => $charging['end_kw'],
            'charged_kw'          => $charging['charged_kw'],
            'credits'             => $charging['credits'],
        ];

        $this->batchChargingCount++;

        // Ha elérjük a batch méretet, akkor végrehajtjuk a beszúrást
        if ($this->batchChargingCount >= self::BATCH_INSERT_SIZE) {
            $this->bulkSaveChargings();
        }
    }

    // Saját info üzenet metódus
    private function info(string $message): void
    {
        if ($this->command) {
            $this->command->info($message);
        } else {
            $this->output->writeln("<info>$message</info>");
        }
    }

    // Saját error üzenet metódus
    private function error(string $message): void
    {
        if ($this->command) {
            $this->command->error($message);
        } else {
            $this->output->writeln("<error>$message</error>");
        }
    }

    // Haladásjelző inicializálása
    private function createProgressBar(int $max): void
    {
        if ($this->command) {
            // Ha Artisan környezetben futunk, használjuk a beépített progressBar-t
            $this->progressBar = $this->command->getOutput()->createProgressBar($max);
        } else {
            // Különben használjuk a saját konzolos kimenetet
            $this->progressBar = new ProgressBar($this->output, $max);
        }

        // Haladásjelző formázása
        $this->progressBar->start();
    }

    // Haladásjelző frissítése
    private function advanceProgressBar(int $step = 1): void
    {
        if ($this->progressBar) {
            $this->progressBar->advance($step);
        }
    }

    // Haladásjelző befejezése
    private function finishProgressBar(): void
    {
        if ($this->progressBar) {
            $this->progressBar->finish();
            $this->output->writeln('');  // Új sor a haladásjelző után
        }
    }

    public function run(): void
    {
        $startTime = microtime(true);

        try {
            // Teljesítmény optimalizációk
            DB::beginTransaction();
            DB::disableQueryLog();

            // Használjuk a cache-t a Renthistory Factory-ban ha lehetséges
            if (method_exists(Renthistory::class, 'enableCache')) {
                Renthistory::enableCache();
            }
            $totalRecords = 1000;
            $chunkSize = 200;  // Főtábla chunk méret

            // Haladásjelző inicializálása
            $this->info("Összesen létrehozandó bérlések: $totalRecords");
            $this->createProgressBar($totalRecords);

            for ($offset = 0; $offset < $totalRecords; $offset += $chunkSize) {
                $batchStart = microtime(true);
                $currentChunkSize = min($chunkSize, $totalRecords - $offset);

                // Létrehozzuk a bérlés adatokat
                $rentData = [];
                $rentExtras = [];

                // Factory példányok előkészítése
                $factories = [];
                for ($i = 0; $i < $currentChunkSize; $i++) {
                    $factories[] = Renthistory::factory();
                }

                // Párhuzamos objektum létrehozás előkészítése (ha támogatott)
                // Vagy alrendszerként elindítjuk őket egymás után
                foreach ($factories as $index => $factory) {
                    $rentHistory = $factory->make();
                    $currentRentData = $rentHistory->toArray();

                    // Ideiglenesen tároljuk a kapcsolódó adatokat
                    $rentExtras[] = [
                        'osszesParkolasEsemeny' => $currentRentData['osszesParkolasEsemeny'] ?? [],
                        'chargeData' => $currentRentData['chargeData'] ?? []
                    ];

                    // Töröljük a nem szükséges adatokat
                    unset($currentRentData['osszesParkolasEsemeny'], $currentRentData['chargeData']);
                    $rentData[] = $currentRentData;
                }

                // Bérlések beszúrása
                if (!empty($rentData)) {
                    $firstId = DB::table('car_user_rents')->insertGetId($rentData[0]);
                    if (count($rentData) > 1) {
                        DB::table('car_user_rents')->insert(array_slice($rentData, 1));
                    }
                }

                // Feldolgozzuk a kapcsolódó adatokat
                for ($i = 0; $i < count($rentExtras); $i++) {
                    $rentId = $firstId + $i;

                    // Parkolási események feldolgozása
                    foreach ($rentExtras[$i]['osszesParkolasEsemeny'] as $parkolas) {
                        $this->addParkingToBatch($rentId, $parkolas);
                    }

                    // Töltési események feldolgozása
                    foreach ($rentExtras[$i]['chargeData'] as $charging) {
                        $this->addChargingToBatch($rentId, $charging);
                    }
                }

                // Maradék batch-ek mentése
                $this->bulkSaveParkings();
                $this->bulkSaveChargings();

                // Haladásjelző frissítése
                $this->advanceProgressBar($currentChunkSize);

                $batchTime = microtime(true) - $batchStart;
                $this->info("\t" . $offset + $currentChunkSize . " db lezárt bérlés legenerálva.");

                // Memória felszabadítása
                unset($rentData, $rentExtras, $factories);
                if (function_exists('gc_collect_cycles')) {
                    gc_collect_cycles();
                }
            }

            // Haladásjelző befejezése
            $this->finishProgressBar();

            DB::commit();

            $memoryAfter = memory_get_usage();
            $totalTime = microtime(true) - $startTime;

            $this->info("Műveletek befejezve!");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CarUserRentsSeederben hiba volt a lefutáskor: ' . $e->getMessage());
            Log::error('Részletes hiba üzenete: ' . $e->getTraceAsString());
            $this->error("Hiba: " . $e->getMessage());
            throw $e;
        } finally {
            // Visszaállítjuk az eredeti állapotot
            if (method_exists(Renthistory::class, 'disableCache')) {
                Renthistory::disableCache();
            }
        }
    }
}
