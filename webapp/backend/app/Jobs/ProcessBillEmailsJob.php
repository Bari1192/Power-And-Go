<?php

namespace App\Jobs;

use App\Mail\BillSummary;
use App\Mail\ChargeFineMail;
use App\Mail\RentalSummaryMail;
use App\Models\Bill;
use App\Models\Car;
use App\Models\User;
use App\Policies\CarRefreshService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessBillEmailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        try {
            // Számlák lekérése, amelyek még nem lettek elküldve
            $bills = Bill::where('is_sent', false)->get();

            Log::info('Feldolgozandó számlák száma: ' . count($bills));

            foreach ($bills as $bill) {
                try {
                    // Felhasználó és autó lekérése a számla alapján
                    $user = User::find($bill->user_id);
                    $car = Car::find($bill->car_id);

                    if (!$user || !$car) {
                        Log::error('Nem található felhasználó vagy autó a számlához', [
                            'bill_id' => $bill->id,
                            'user_id' => $bill->user_id,
                            'car_id' => $bill->car_id
                        ]);
                        continue;
                    }

                    // A számla típusától függően más-más e-mailt küldünk
                    if ($bill->type === 'rental') {
                        Mail::to($user->email)->send(new RentalSummaryMail($bill));
                        Log::info('Bérlési számla e-mail elküldve', ['bill_id' => $bill->id, 'user_email' => $user->email]);
                    } elseif ($bill->type === 'charging_penalty') {
                        $carRefreshService = new CarRefreshService();
                        Mail::to($user->email)->send(new ChargeFineMail($bill, $user, $car, $carRefreshService));
                        Log::info('Töltési büntetés e-mail elküldve', ['bill_id' => $bill->id, 'user_email' => $user->email]);
                    } else {
                        Log::warning('Ismeretlen számla típus', ['bill_id' => $bill->id, 'type' => $bill->type]);
                        continue;
                    }

                    // Számla megjelölése elküldöttként
                    $bill->is_sent = true;
                    $bill->save();
                } catch (\Exception $e) {
                    Log::error('Hiba a számla feldolgozása során', [
                        'bill_id' => $bill->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            Log::info('E-mail küldési job befejeződött');
        } catch (\Exception $e) {
            Log::error('Kritikus hiba a számlák feldolgozása során', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
