<?php

namespace App\Observers;

use App\Jobs\ProcessBillEmailsJob;
use App\Models\Bill;

class BillObserver
{
    public function created(Bill $bill)
    {
        if ($bill->bill_type == 'rental' || $bill->bill_type == 'charging_penalty') {
            ProcessBillEmailsJob::dispatch([$bill->id]);
        }
    }
}
