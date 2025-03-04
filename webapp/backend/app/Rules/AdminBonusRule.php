<?php

namespace App\Rules;

use App\Interfaces\BonusRuleInterface;
use App\Models\User;

class AdminBonusRule implements BonusRuleInterface
{
    private $adminId;
    private $minutes;
    private $customReason;
    
    public function __construct(int $adminId, int $minutes, string $customReason = null)
    {
        $this->adminId = $adminId;
        $this->minutes = $minutes;
        $this->customReason = $customReason;
    }
    
    public function isEligible(User $user, array $context): bool
    {
        ## Admin általi bónusznál mindig jogosult rá a felhasználó.
        ## Viszont majd az adminisztrátor jogosultságát külön kellene ellenőrizni!
        return true;
    }
    
    public function calculateBonusMinutes(User $user, array $context): int
    {
        return $this->minutes;
    }
    
    public function getSource(): string
    {
        return 'admin';
    }
    
    public function getType(): string
    {
        return 'debit';
    }
    
    public function getReason(User $user, array $context): string
    {
        if ($this->customReason) {
            return $this->customReason;
        }
        
        return "Operátori jóváírás ({$this->minutes} perc)";
    }
}