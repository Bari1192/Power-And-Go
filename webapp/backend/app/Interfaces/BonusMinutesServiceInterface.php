<?php

namespace App\Interfaces;

use App\Models\User;

interface BonusMinutesServiceInterface
{
    public function addDrivingMinutes(User $user, int $minutes);

    public function addBonusMinutes(User $user, int $bonusMinutes, string $sourceOwner, string $type, ?string $reason);

    public function useBonusMinutes(User $user, int $bonusMinutes);

    public function getBonusMinutesBalance(User $user);

    public function getBonusMinutesHistory(User $user);
}
