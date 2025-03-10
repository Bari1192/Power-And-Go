<?php

namespace App\Console\Commands;

use App\Mail\ChargeFineMail;
use App\Mail\RentalSummaryMail;
use App\Models\Bill;
use App\Policies\CarRefreshService;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;

class SendBillEmailsCommand extends Command
{

    protected $signature = 'app:send-bill-emails-command';
    protected $description = 'E-mailek kiküldése az összes "pending" állapotú bills-hez.';

    public function handle()
    {
        try {
            $bills = Bill::where('email_sent', false)->get();
            (int)$feldolgozott = 0;
            $teljes = count($bills);
            $this->info('Feldolgozandó e-mailek száma: ' . $teljes);

            ## Állapotsáv / Progress Bár
            $bar = $this->output->createProgressBar($teljes);
            $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%%');
            $bar->start();
            $processed = 0;
            $successful = 0;
            $failed = 0;

            foreach ($bills as $bill) {
                try {
                    // Load relationships
                    $bill->load(['users', 'cars', 'persons']);

                    if (!$bill->users || !$bill->cars) {
                        Log::error('User or car not found for bill', [
                            'bill_id' => $bill->id,
                            'user_id' => $bill->user_id,
                            'car_id' => $bill->car_id
                        ]);
                        continue;
                    }
                    $email = $bill->users->person->email;

                    if ($bill->bill_type === 'rental') {
                        Mail::to($email)->send(new RentalSummaryMail($bill));
                        Log::info('Rental bill email sent', ['bill_id' => $bill->id]);
                    } elseif ($bill->bill_type === 'charging_penalty') {
                        $carRefreshService = new CarRefreshService();
                        Mail::to($email)->send(new ChargeFineMail($bill, $bill->users, $bill->cars, $carRefreshService));
                        Log::info('Charging penalty email sent', ['bill_id' => $bill->id]);
                    } else {
                        Log::warning('Unknown bill type', ['bill_id' => $bill->id, 'type' => $bill->bill_type]);
                        continue;
                    }

                    Bill::where('id', $bill->id)->update(['email_sent' => 1]);
                    $feldolgozott++;
                } catch (\Exception $e) {
                    Log::error('Error processing bill', [
                        'bill_id' => $bill->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $failed++;
                }
                $bar->advance();
                $processed++;
            }
            $bar->finish();
            $this->newLine(2);
            $this->info('E-mailek kiküldése sikeresen megtörtént!');
            $this->table(
                ['Feldolgozott', 'Sikeres', 'Sikertelen'],
                [[$processed, $successful, $failed]]
            );

            Log::info('E-mailek kiküldése sikeresen megtörtént!');
        } catch (\Exception $e) {
            Log::error('Kritikus hiba az alábbiakban:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
