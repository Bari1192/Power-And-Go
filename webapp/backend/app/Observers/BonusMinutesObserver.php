<?php

namespace App\Observers;

use App\Models\BonusMinutesTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class BonusMinutesObserver
{
    public function created(BonusMinutesTransaction $transaction)
    {
        $actionType = $transaction->type === 'debit' ? 'jóváírás' : 'felhasználás';
        $user = User::find($transaction->user_id);
        $userName = $user ? $user->user_name : 'Ismeretlen felhasználó';

        Log::info("Bónusz perc {$actionType} ({$transaction->amount} perc):", [
            'user_id' => $transaction->user_id,
            'user_name' => $userName,
            'amount' => $transaction->amount,
            'current_balance' => $user ? $user->bonus_minutes : 0,
            'type' => $transaction->type,
            'source' => $transaction->source,
            'reason' => $transaction->reason
        ]);

        ## Itt pl értesítések küldése
        ## a felhasználónak, ha elérte a mérföldkövet
        // if ($transaction->source === 'plant_tree' && $transaction->type === 'credit') {
        ## Értesítés küldése (pl. email, push notification, stb.)
        ## NotificationService::send($transaction->user_id, 'bonus_earned', $transaction->amount);
        // }
    }
}
/**
 * Összesen a User modelben létrehoztam a 'bonus_minutes','driving_minutes', és a 'plant_tree' mezőket. 
 * A bonus_minutes -ben tárolom a jóváírt bónusz perceket az adott kampányban
 * A driving_minutes-ben a levezetett perceket az adott kampányban, amik után majd a jóváírást kapja a felhasználó.
 * A plant_tree boolean pedig azt határozza meg, hogy az adott felhasználó a programba "feliratkozott" -e. Ebben az esetben
 * pedig majd 1 ft / vezetési perc hozzájárulást fizet a faültetési programba, "cserébe a bónuszokért".
 */
