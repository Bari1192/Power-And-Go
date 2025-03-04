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
            ## Aktuális percek + új percek
            $totalMinutes = $user->driving_minutes + min($minutes, 200);

            ## A teljes "200 perces ciklusok" száma
            $completedCycles = floor($totalMinutes / 200);

            ## Maradék percek >> következő ciklushoz
            $remainingMinutes = floor($totalMinutes % 200);

            ## Bónusz percek hozzáadása minden teljes ciklus után
            if ($completedCycles >= 1.0) {
                $bonusToAdd = 20;         # 20 perc bón.
                if ($remainingMinutes < 1) {
                    $remainingMinutes = 200;
                }

                $this->addBonusMinutes(
                    $user,              ## kinek
                    $bonusToAdd,        ## mennyit
                    'plant_tree',       ## "ki" adja?
                    'debit',            ## Jóváírás
                    "Gratulálunk! A Gurulj A Fákért programunk keretében 200 levezetett perced után járó 20 bónusz perced jóváírásra került!
                    Tudtad, hogy a következő 20 perc bónuszért már csak {$remainingMinutes} percet kell gurulnod?"
                );
            }
            ## Maradék percek mentése a kövihez
            $user->driving_minutes = $remainingMinutes;
            $user->save();

            ## A faültetési program 1 Ft / perc hozzájárulás 
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
            ## Logolás
            return false;
        }
    }

    public function addBonusMinutes(User $user, int $bonusMinutes, string $sourceOwner, string $type, ?string $reason)
    {
        DB::beginTransaction();
        BonusMinutesTransaction::create([
            'user_id' => $user->id,
            'amount' => $bonusMinutes,
            'source' => $sourceOwner,
            'type' => $type,
            'reason' => $reason,
        ]);
        ## Idő jóváírás + A lejárati idő "újraindul".
        $user->bonus_minutes += $bonusMinutes;
        $user->bonus_min_exp = Carbon::now()->addDays(30);
        $user->save();
        DB::commit();
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
/**
 * Amikor majd a számlát kiállítja a rendszer és a vezetési időből levonjuk (ha felhasználja a bónusz perceket / beváltja),
 * de kevesebb van neki, mint amennyi vezetési perce, akkor tudjuk kezelni ezt a maradék időt,
 * amire majd kiszámoljuk a vezetési percdíj költségét. Ezzel rugalamasan a számla készítésbe tudom rakni majd.
 * 
 * Ha meg 0 értékkel jön vissza, akkor felhasználta és a vezetési percdíj majd 0 lesz.
 * Ha nem az egészre elegendő bónusz perce van, akkor pedig rész-levonással a maradékra könnyebb így számolni.
 * Viszont fontos, hogy ha valamiért telibe 0 perc "maradna hátra, akkor a 200 PERCET írjuk ki neki, mint 'teljesítendő'.
 * 
 * BonusMinutesTransaction táblába (create::) részével. Ez lenne az 'admin' source. Az e-mail szövege most mellékes, azt már megírtam.
 * ####################################################################################################################################

 * ###################### 2. FELVONÁS ######################
 *  Most szeretném továbbfejleszteni a projektemet. Tehát megvan az interface, service, observer. Az interface-ben:
 *      <> addDrivingMinutes
 *      <> addBonusMinutes
 *      <> useBonusMinutes
 *      <> getBonusMinutesBalance
 *      <> getBonusMinutesHistory
 *  Szeretném az alábbi funkciókat létrehozni (kiegészíteni a mostani projektet), miszerint:
 * 
 ** Frontend oldalon majd az ügyfélszolgálatos (továbbiakban: üf / üf-szolis) jóvá tudjon írni 5-10-15 percet az adott felhasználónak, a pontos perceket majd meghatározom én. Ezt ugyanúgy majd hozzá kell adni a BonusMinutesTransaction táblába (create::) részével. Ez lenne az 'admin' source. Az e-mail szövege most mellékes, azt már megírtam.
 ** Továbbá be szeretném vezetni azt is, hogy amennyiben napi bérlése van a user-nek, és a bérlés lezárásakor a töltés százalék min. 70.0%, akkor is jóvá szeretnék írni 45 bónusz-percet a felhasználónak. Indoka pedig a 'napi_berles_70', amit a 'system' adna automatikusan ha a fentiek teljesülnek.
 ** Első körben ezeket szeretném bevezetni. Arra gondoltam, hogy:
 **      <> A BonusMinutesServiceInterface felhasználásával az addBonuszMinutes függvényé meghívásával teljesülne ez.
 **      <> Mivel a függvény úgy is kezelné a BonusMinutesTransaction::create-tel a létrehozást és a paramétereket.
 **      <> Fontos, hogy amennyiben a napi bérlés során 200 percet vezetett, akkor 1 db 20 perces 'plant_tree' bonusz percet kapjon, amennyiben ugyanúgy fel van iratkozva a kampányra. Továbbá ettől függetlenül ha teljesíti, akkor érvényesülnie kell a 70% feletti (napi bérlés esetén csak) bérlés lezárása után a 45 bónusz perc jóváírást is. Például egy 2 napig tartó bérlés esetén 78.62%-on zárja le a felhasználó a bérlést és 276 percet vezetett és "benne van" a 'plant_tree' kampányban akkor:
 **      <> Jóváírásra kerüljön egyszer a 20 bónusz perc, (a maradék 76-ot ebben az esetben nem görgetjük át, mivel egy teljes bérlési ciklus alatt maximum 200 percet írunk jóvá.)
 **      <> Jóváírjuk neki a 45 bónusz percet a 78.62%-os töltés zárása miatt.
 **      <> (Amennyiben töltött az autóba, azt külön kezeljük, az így is érvényesül már, azzal most nem foglalkozunk).
 **      <> A 276 perc vezetés végett a már meglévő 'hozzájárulás' - contributions - részhez hozzáadjuk a vezetési percet (276) összeget, amit levonunk a $user->account_balance értékből.
 
 * Így gondoltam el a kialakítását backend oldalon ennek első körben. Utána majd Frontenden is meg kéne oldani, hogy a DASHBOARD részén
 * Felvegyük ezt a bónusz perces részt, ahol majd ki lehessen választani, hogy
 *       <> Kinek?
 *       <> Mennyi percet?
 *       <> Jóváírunk / levonunk? (bár levonni lehet nem kéne.)
 *       <> A `source` ezesetben az 'admin' lenne - üf-szolis.
 */
