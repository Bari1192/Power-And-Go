<?php

namespace App\Rules;

use App\Interfaces\BonusRuleInterface;

class PlantTreeCampaignRule implements BonusRuleInterface
{
    public function isEligible($user,$context): bool
    {
        // Csak akkor jogosult, ha részt vesz a programban
        if (!$user->plant_tree) {
            return false;
        }
        return $user->driving_minutes >= 200;
    }
    public function calculateBonusMinutes($user, array $context): int
    {
        ## Mindig 20 perc bónusz jár
        return 20;
    }

    public function getSource(): string
    {
        return 'plant_tree';
    }

    public function getType(): string
    {
        return 'debit';
    }

    public function getReason($user, array $context): string
    {
        ## A maradék percek számát az addDrivingMinutes fgv-nyel már beállítottam!
        $remainingMinutes = $user->driving_minutes;

        return "Gratulálunk! A Gurulj A Fákért programunk keretében 200 levezetett perced után járó " .
            "20 bónusz perced jóváírásra került! " .
            "Tudtad, hogy a következő 20 perc bónuszért már csak {$remainingMinutes} percet kell gurulnod?";
    }
}
