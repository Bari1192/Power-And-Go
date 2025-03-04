<?php

namespace App\Providers;

use App\Console\Commands\BonusMinutesExpirationManager;
use App\Models\Bill;
use App\Models\User;
use App\Observers\BillObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        ## Command regisztráció
        $this->commands([
            BonusMinutesExpirationManager::class,
        ]);
    }

    public function boot(): void
    {
        Bill::observe(BillObserver::class);
        Model::shouldBeStrict();
        TimeFormatProvider::class;
        if ($this->app->runningInConsole()) {
            $schedule = $this->app->make(\Illuminate\Console\Scheduling\Schedule::class);
            $schedule->command('app:bonus-minutes-expiration-manager')
                ->daily()
                ->at('02:30')
                ->appendOutputTo(storage_path('logs/bonus-minutes-expiration.log'));
        }

        Gate::define("create-user", function (User $user) {
            # Az admin hozhat létre csak a User-t
            return "Admin" == $user->name;
        });
        Gate::define("update-user", function (User $user) {
            # Az admin módosíthatja csak a User-t
            return "Admin" == $user->name;
            // return "Admin" == $user->name || $car->owner == $user->id;
        });
        Gate::define("delete-user", function (User $user) {
            # Az admin törölhet csak a User-t
            return "Admin" == $user->name;
        });

        ## Az E-mail Blade-hez a time formatter function miatt
        Blade::directive('formatDuration', function ($minutes) {
            return "<?php echo TimeFormatHelper::formatDuration($minutes); ?>";
        });
    }
}
