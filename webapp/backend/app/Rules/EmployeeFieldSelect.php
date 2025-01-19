<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmployeeFieldSelect implements ValidationRule
{
    protected $allowedFieldsChoose;

    public function __construct()
    {
        $this->allowedFieldsChoose =
            [
                'Marketing',
                'Adminisztráció',
                'Ügyfélszolgálat',
                'Humánerőforrás',
                'Flottakezelés',
                'IT',
                'Menedzsment',
                'Pénzügy',
                'Jog',
            ];
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array($value,$this->allowedFieldsChoose)) {
            $fail("A megadott '{$value}' terület hibás / érvénytelen.");
        }
    }
}
