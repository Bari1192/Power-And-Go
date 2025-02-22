<?php

namespace App\Observers;

use App\Mail\RentalSummaryMail;
use App\Models\Bill;
use App\Models\Person;
use Illuminate\Support\Facades\Mail;

class BillObserver
{
    public function created(Bill $bill)
    {
        ## Only send email for RENTAL TYPE BILLS!
        if ($bill->bill_type === 'rental') {
            $bill->load(['users', 'cars.users', 'persons']);
            $person = Person::find($bill->person_id);
            
            if ($person && $person->email) {
                Mail::to($person->email)->send(new RentalSummaryMail($bill));
            }
        }
    }
}
