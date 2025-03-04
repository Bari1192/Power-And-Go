<?php

namespace App\Interfaces;

use App\Models\User;

interface BonusRuleInterface
{
    ## Jogosult / feliratkozott?
    public function isEligible(User $user, array $context): bool;

    ## Van-e admin joga ehhez?
    // public function isAdminEligible(User $user): bool;

    ## Kiszámítja, hogy mennyi bónuszpercet írjon jóvá
    public function calculateBonusMinutes(User $user, array $context): int;

    ## Ki / Mi írta jóvá?
    public function getSource(): string;

    public function getType(): string;

    public function getReason(User $user, array $context): string;
}
