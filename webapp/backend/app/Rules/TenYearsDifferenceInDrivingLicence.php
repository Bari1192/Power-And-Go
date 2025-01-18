<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TenYearsDifferenceInDrivingLicence implements ValidationRule
{
    protected $license_startDate;

    public function __construct($license_startDate)
    {
        $this->license_startDate = $license_startDate;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->license_startDate || !strtotime($this->license_startDate)) {
            $fail("A kezdési dátum érvénytelen.");
            return;
        }

        $startDate = Carbon::parse($this->license_startDate);
        $endDate = Carbon::parse($value); 

        if (!$startDate->addYears(10)->isSameDay($endDate)) {
            $fail("Hibás a(z) '$attribute' mező. A jogosítvány kiállítási és érvényességi ideje között pontosan 10 évnek kell lennie!");
        }
    }
}
