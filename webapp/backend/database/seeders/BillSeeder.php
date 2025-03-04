<?php

namespace Database\Seeders;

use App\Jobs\ProcessBillEmailsJob;
use App\Mail\ChargeFineMail;
use App\Mail\RentalSummaryMail;
use App\Models\Bill;
use App\Models\Car;
use App\Models\Person;
use App\Models\User;
use App\Policies\BillService;
use App\Policies\CarRefreshService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

class BillSeeder extends Seeder
{
    private CarRefreshService $carRefreshService;
    private BillService $billService;

    public function __construct(CarRefreshService $carRefreshService)
    {
        $this->carRefreshService = $carRefreshService;
        $this->billService = new BillService($carRefreshService);
    }

    public function run(): void
    {
        $chunkSize = 50;
        $processedCount = 0;
        $totalBills = 0;

        try {
            DB::beginTransaction();

            ## Car és User adatok előzetes betöltése
            $carIds = DB::table('car_user_rents')->where('rentstatus', 2)->pluck('car_id')->unique();
            $userIds = DB::table('car_user_rents')->where('rentstatus', 2)->pluck('user_id')->unique();

            $cars = Car::whereIn('id', $carIds)->get()->keyBy('id');
            $users = User::whereIn('id', $userIds)->with('person')->get()->keyBy('id');

            ## Összes feldolgozandó bérlés számának lekérése a folyamatjelzőhöz
            $totalRentals = DB::table('car_user_rents')->where('rentstatus', 2)->count();
            echo "Összesen feldolgozandó bérlések: $totalRentals\n";

            ## Bérlések feldolgozása chunk-okban
            DB::table('car_user_rents')
                ->where('rentstatus', 2)
                ->orderBy('id')
                ->chunkById($chunkSize, function ($rentals) use (
                    &$processedCount,
                    &$totalBills,
                    $cars,
                    $users,
                    $totalRentals
                ) {
                    ## A BillService segítségével generáljuk a számlákat
                    $generatedBills = $this->billService->createBulkBills($rentals, $cars, $users);

                    ## Számlák tömeges beszúrása
                    if (!empty($generatedBills['rentalBills'])) {
                        // Módosítás: tömeges beszúrás email_sent=false alapértékkel
                        $billsToInsert = [];
                        foreach ($generatedBills['rentalBills'] as $billData) {
                            $billData['email_sent'] = false;
                            $billsToInsert[] = $billData;
                        }

                        ## Tömeges INSERT
                        if (!empty($billsToInsert)) {
                            DB::table('bills')->insert($billsToInsert);
                            $totalBills += count($billsToInsert);
                        }
                    }

                    if (!empty($generatedBills['penaltyBills'])) {
                        ## tömeges beszúrás email_sent===false !!!! alapértékkel
                        $billsToInsert = [];
                        foreach ($generatedBills['penaltyBills'] as $billData) {
                            $billData['email_sent'] = false;
                            $billsToInsert[] = $billData;
                        }

                        // Tömeges beszúrás a teljesítmény javításáért
                        if (!empty($billsToInsert)) {
                            DB::table('bills')->insert($billsToInsert);
                            $totalBills += count($billsToInsert);
                        }
                    }

                    $processedCount += count($rentals);
                    $percentage = round(($processedCount / $totalRentals) * 100, 1);
                    echo "Feldolgozva: $processedCount / $totalRentals bérlés ($percentage%)\n";
                });

            DB::commit();
            echo "Összes számla sikeresen létrehozva: $totalBills db.\n\n";

            $billsToProcess = Bill::where('email_sent', false)->pluck('id')->toArray();
            $totalEmails = count($billsToProcess);
            echo "Összesen küldendő e-mail: $totalEmails db.\n";

            $emailBatchSize = 10;
            $batches = array_chunk($billsToProcess, $emailBatchSize);
            $processedBatches = 0;
            foreach ($batches as $batch) {
                ProcessBillEmailsJob::dispatch([$batch]);
                $processedBatches++;
            }

            echo "E-mail feldolgozás beütemezve.\n";
        } catch (Exception $e) {
            DB::rollBack();
            echo "Kritikus hiba történt: " . $e->getMessage() . "\n";
            Log::error('BillSeeder kritikus hiba: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
        $this->command->info("E-mail küldési parancs végrehajtása az e-mailekre...\n");
        \Illuminate\Support\Facades\Artisan::call('app:send-bill-emails-command', ['--verbose' => true]);
    }
}
