<?php

namespace App\Services;

use App\Interfaces\BonusRuleInterface;
use App\Models\User;
use App\Rules\AdminBonusRule;
use App\Rules\DailyRentalBonusRule;

class BonusRuleService
{
    private $bonusMinutesService;
    private $dailyRentalBonusRule;
    private $rules = [];

    public function __construct(BonusMinutesService $bonusMinutesService, DailyRentalBonusRule $dailyRentalBonusRule)
    {
        $this->bonusMinutesService = $bonusMinutesService;
        $this->dailyRentalBonusRule = $dailyRentalBonusRule;
        $this->registerRule($dailyRentalBonusRule, $bonusMinutesService);
    }

    public function registerRule(BonusRuleInterface $rule): void
    {
        $this->rules[] = $rule;
    }

    public function applyEligibleRules(User $user, array $context): array
    {
        $appliedRules = [];

        foreach ($this->rules as $rule) {
            if ($rule->isEligible($user, $context)) {
                $bonusMinutes = $rule->calculateBonusMinutes($user, $context);

                if ($bonusMinutes > 0) {
                    $this->bonusMinutesService->addBonusMinutes(
                        $user,
                        $bonusMinutes,
                        $rule->getSource(),
                        $rule->getType(),
                        $rule->getReason($user, $context)
                    );

                    $appliedRules[] = [
                        'source' => $rule->getSource(),
                        'type' => $rule->getType(),
                        'minutes' => $bonusMinutes,
                        'reason' => $rule->getReason($user, $context)
                    ];
                }
            }
        }

        return $appliedRules;
    }

    // public function applyAdminBonus(User $user, int $adminId, int $minutes, string $reason = null): void
    // {
    //     $rule = new AdminBonusRule($adminId, $minutes, $reason);

    //     $this->bonusMinutesService->addBonusMinutes(
    //         $user,
    //         $minutes,
    //         $rule->getSource(),
    //         $rule->getType(),
    //         $rule->getReason($user, [])
    //     );
    // }
}
