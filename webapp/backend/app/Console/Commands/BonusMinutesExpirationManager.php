<?php

namespace App\Console\Commands;

use App\Mail\BonusMinutesExpirationWarning;
use App\Models\User;
use App\Services\BonusMinutesService;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BonusMinutesExpirationManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bonus-minutes-expiration-manager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Figyelmezteti a felhasználókat a hamarosan lejáró bónusz perceikről és törli a lejárt bónusz perceket';


    private $bonusMinutesService;
    public function __construct(BonusMinutesService $bonusMinutesService)
    {
        parent::__construct();
        $this->bonusMinutesService = $bonusMinutesService;
    }

    public function handle()
    {
        $this->handleSoonToExpireMinutes();
        $this->handleExpiredMinutes();
        $this->info('A bónusz-percer lejáratának kezelése sikeresen befejeződött.');
        return 0;
    }

    # 7 napon belül
    private function handleSoonToExpireMinutes()
    {
        $warningDate = Carbon::now()->addDays(7)->startOfDay();
        $closeToExpireSoon = User::where('bonus_minutes', '>', 0)
            ->whereDate('bonus_min_exp', $warningDate->toDateString())
            ->with('persons')
            ->get();

        ## végigmenni rajtuk
        foreach ($closeToExpireSoon as $transaction) {
            if ($transaction->persons) {
                try {
                    Mail::to($transaction->persons->email)
                        ->send(new BonusMinutesExpirationWarning(
                            $transaction->persons->last_name,
                            $transaction->bonus_minutes,
                            $transaction->bonus_min_exp
                        ));


                    $this->info("7 napon belül lejáró bónusz-percek értesítői kiküldésre kerültek: {$transaction->persons->email}");
                } catch (Exception $e) {
                    $this->error("E-mail küldési hiba: " . $e->getMessage());
                    Log::error("E-mail küldési hiba: " . $e->getMessage(), [
                        'user_id' => $transaction->id,
                        'email' => $transaction->persons->email ?? 'nincs e-mail'
                    ]);
                }
            } else {
                $this->warn("Nincs kapcsolódó személy rekord: User ID {$transaction->id}");
            }
        }
    }

    # Lejártak nullázása
    private function handleExpiredMinutes()
    {
        $this->info('Lejárt bónusz percek ellenőrzése...');

        $today = Carbon::now()->startOfDay();
        $expiredTransaction = User::where('bonus_minutes', '>', 0)
            ->whereDate('bonus_min_exp', '<', $today->toDateString())
            ->get();

        $this->info('Találat: ' . $expiredTransaction->count() . ' lejárt egyenleg');

        foreach ($expiredTransaction as $transaction) {
            $oldBalance = $transaction->bonus_minutes;
            $transaction->bonus_minutes = 0;
            $transaction->save();

            $this->info("A lejárt bónusz-percek kezelése megtörtént: User ID {$transaction->id}, összesen: {$oldBalance} perc.");

            Log::info("Bónusz percek lejártak", [
                'user_id' => $transaction->id,
                'expired_balance' => $oldBalance,
                'expiration_date' => $transaction->bonus_min_exp
            ]);
        }
    }
}
