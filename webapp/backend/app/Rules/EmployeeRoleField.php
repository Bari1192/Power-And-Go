<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmployeeRoleField implements ValidationRule
{
    protected $field;
    protected $roles;

    public function __construct(string $field)
    {
        $this->field=$field;

        $this->roles=[
            'Marketing' => ['Social Media kezelő', 'Social Media Menedzser', 'Kampány-tervező', 'Kampánymenedzser'],
            'Adminisztráció' => ['Foglalásrögzítő', 'Irodai adminisztrátor'],
            'Ügyfélszolgálat' => ['Baleseti-Callcenter', 'Vállalati-Callcenter', 'English-helpdesk', 'Panaszkezelés'],
            'Humánerőforrás' => ['Toborzó', 'HR adminisztrátor'],
            'Flottakezelés' => ['Flottamenedzser', 'Logisztikai ügyintéző', 'Kárbejelentő', 'Alvállalkozói flottakezelő'],
            'IT' => ['Adatbázis Fejlesztő', 'Alkalmazás Fejlesztő', 'Webapplikáció-Fejlesztő', 'Tesztelő', 'Backend-Fejlesztő', 'Rendszermérnök', 'Termékmenedzser'],
            'Menedzsment' => [
                'Projektvezető',
                'Üzletfejlesztési menedzser',
                'HR menedzser',
                'Kommunikációs menedzser',
                'Flottakezelő menedzser',
                'IT menedzser',
            ],
            'Pénzügy' => [
                'Értékesítés',
                'Könyvelés',
                'Vállalati szerződés-kezelő',
                'Bérszámfejtés',
                'Szerződés-kezelés',
            ],
            'Jog' => ['Jogi tanácsadó', 'Ügyvéd'],
        ];
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validatedRoles=$this->roles[$this->field] ?? $fail('Hiba a munkakörbesorolásban.');

        if (!$validatedRoles || !in_array($value,$validatedRoles)) {
            $fail("A megadott munkakör '{$value}' nem érvényes a(z) '{$this->field}' kategóriában.");
        }
    }
}
