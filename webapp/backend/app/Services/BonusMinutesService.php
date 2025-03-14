<?php

namespace App\Services;

use App\Interfaces\BonusMinutesServiceInterface;
use App\Models\BonusMinutesTransaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BonusMinutesService implements BonusMinutesServiceInterface
{
    public function addDrivingMinutes(User $user, int $minutes)
    {
        DB::beginTransaction();
        try {
            ## Aktuális hátralévő percek a 200-ból
            $remainingMinutes = $user->driving_minutes;

            /**
             * Egy bérlési ciklus / lezárás során 
             * max 200 percet kaphat
             * Ezzel "kibackelve" a ""túlszámítást""
             */
            $minutes = min(200, $minutes);

            ## Ha több percet vezet, mint ami a 200-ból hátra van,
            ## akkor bónusz percet kap!
            if ($minutes >= $remainingMinutes) {
                ## Elhasználta a hátralévő perceket ebből a ciklusból
                $usedMinutes = $remainingMinutes;
                ## Még ennyi perc a következő ciklusból (áthozott - az új)
                $extraMinutes = $minutes - $remainingMinutes;

                ## Bónuszperc jóváírása a teljes "ciklus" miatt.
                $bonusToAdd = 20;
                $this->addBonusMinutes(
                    $user,              ## kinek
                    $bonusToAdd,        ## mennyit
                    'plant_tree',       ## "ki" adja?
                    'debit',            ## Jóváírás
                    "Gratulálunk! A Gurulj A Fákért programunk keretében 200 levezetett perced után járó 20 bónusz perced jóváírásra került!
                    Tudtad, hogy a következő 20 perc bónuszért már csak " . (200 - $extraMinutes % 200) . " percet kell gurulnod?"
                );

                ## Az új ciklusból hátralévő percek számítása
                ## Ha az extraMinutes > 200, akkor újabb ciklusokat is teljesíthet
                $completedNewCycles = floor($extraMinutes / 200);
                $remainingMinutesInNewCycle = 200 - ($extraMinutes % 200);

                ## Ha további teljes ciklusokat is vezetett
                for ($i = 0; $i < $completedNewCycles; $i++) {
                    $this->addBonusMinutes(
                        $user,
                        $bonusToAdd,
                        'plant_tree',
                        'debit',
                        "Gratulálunk! További 200 levezetett perced után újabb 20 bónusz perced jóváírásra került!
                        Tudtad, hogy a következő 20 perc bónuszért már csak $remainingMinutesInNewCycle percet kell gurulnod?"
                    );
                }

                ## Hátralévő percek frissítése
                $user->driving_minutes = $remainingMinutesInNewCycle;
            } else {
                ## Csak csökkentjük a hátralévő perceket
                $user->driving_minutes = $remainingMinutes - $minutes;
            }

            $user->save();
            ## A faültetési program "LEVONÁSA" ITT!
            ## 1 Ft / perc a hozzájárulás >> Számlához adni!
            $treeContribution = $minutes;
            if ($user->plant_tree) {
                $user->contributions += $treeContribution;
                $user->account_balance -= $treeContribution;
                $user->save();
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Hiba a vezetési percek rögzítése során: ' . $e->getMessage());
            return false;
        }
    }
    public function addBonusMinutes(User $user, int $bonusMinutes, string $sourceOwner, string $type, ?string $reason)
    {
        DB::beginTransaction();
        try {
            BonusMinutesTransaction::create([
                'user_id' => $user->id,
                'amount' => $bonusMinutes,
                'source' => $sourceOwner,
                'type' => $type,
                'reason' => $reason,
            ]);

            /**      A bónusz-percek jóváírása a user-nek
             *       FONTOS! A bónusz-perc "szavatossága" újraindul
             *       [Most] + 30 nap lesz újra a lejárat. */

            $user->bonus_minutes += $bonusMinutes;
            $user->bonus_min_exp = Carbon::now()->addDays(30);
            $user->save();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Hiba a bónusz percek hozzáadása során: ' . $e->getMessage());
            return false;
        }
    }
    public function useBonusMinutes(User $user, int $bonusMinutes)
    {
        try {
            $user = User::where('id', $user->id)->firstOrFail();
            DB::beginTransaction();

            $actualMinutesToUse = min($user->bonus_minutes, $bonusMinutes);
            if ($actualMinutesToUse > 0) {
                $user->bonus_minutes -= $actualMinutesToUse;

                BonusMinutesTransaction::create([
                    'user_id' => $user->id,
                    'amount' => $actualMinutesToUse,
                    'source' => 'user',
                    'type' => 'credit',
                    'reason' => "$user->user_name felhasznált $actualMinutesToUse bónusz-percet a bérlésére. Összesen $user->bonus_minutes perce maradt.",
                ]);

                $user->save();
            }

            DB::commit();
            return $actualMinutesToUse;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Bónusz perc felhasználási hiba: ' . $e->getMessage());
            return 0;
        }
    }
    public function getBonusMinutesBalance(User $user)
    {
        return User::select('bonus_minutes')->where('id', $user->id)->firstOrFail()->bonus_minutes;
    }

    public function getBonusMinutesHistory(User $user)
    {
        return BonusMinutesTransaction::where('user_id', $user->id)->get();
    }
}
