<?php

namespace App\Rules;

use App\Interfaces\BonusRuleInterface;

class DailyRentalBonusRule implements BonusRuleInterface
{
    public function isEligible($user, array $context): bool
    {
        $isDailyRental = $context['is_daily_rental'] ?? false;
        $endPercent = $context['end_percent'] ?? 0;
        return $isDailyRental && $endPercent >= 70.0;
    }
    public function calculateBonusMinutes($user, $inputData): int
    {
        return 45;
    }

    public function getSource(): string
    {
        return 'system';
    }
    public function getType(): string
    {
        return 'debit';
    }

    public function getReason($user, array $context): string
    {
        $endPercent = $context['end_percent'] ?? 70;
        return "Köszönjük, hogy {$endPercent}% feletti töltöttséggel zártad a napi bérlésed! " .
            "Jutalomként 45 bónusz percet kaptál.";
    }
}
