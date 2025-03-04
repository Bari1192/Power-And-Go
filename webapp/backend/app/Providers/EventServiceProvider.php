<?php

namespace App\Providers;

use App\Models\Bill;
use App\Models\BonusMinutesTransaction;
use App\Observers\BillObserver;
use App\Observers\BonusMinutesObserver;
use Carbon\Laravel\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();
        BonusMinutesTransaction::observe(BonusMinutesObserver::class);
    }
}
