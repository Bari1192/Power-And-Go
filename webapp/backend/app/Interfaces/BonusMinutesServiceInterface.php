<?php

namespace App\Interfaces;

use App\Models\User;

interface BonusMinutesServiceInterface
{
    // public function isUserEligible(User $user): bool;

    public function addDrivingMinutes(User $user, int $minutes);

    # Kinek >> Mennyit >> Ki/Mi adta? >> Miért?
    public function addBonusMinutes(User $user, int $bonusMinutes, string $sourceOwner, string $type, ?string $reason);

    ## Melyik user használja fel >> Mennyit >> Hogyan?
    public function useBonusMinutes(User $user, int $bonusMinutes);

    public function getBonusMinutesBalance(User $user);

    public function getBonusMinutesHistory(User $user);
}
