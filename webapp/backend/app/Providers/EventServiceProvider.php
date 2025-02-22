<?php

namespace App\Providers;

use App\Models\Bill;
use App\Observers\BillObserver;
use Carbon\Laravel\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();
        Bill::observe(BillObserver::class);
    }
}
