<?php

namespace App\Interfaces;

use App\Models\User;

interface BonusRuleInterface
{
    public function isEligible(User $user, array $context): bool;

    public function calculateBonusMinutes(User $user, array $context): int;

    public function getSource(): string;

    public function getType(): string;

    public function getReason(User $user, array $context): string;
}
