<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Routing\Route;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::shouldBeStrict();
        TimeFormatProvider::class;

        Gate::define("create-user", function(User $user) {
            # Az admin hozhat létre csak a User-t
            return "Admin" == $user->name;
        });
        Gate::define("update-user", function(User $user) {
            # Az admin módosíthatja csak a User-t
            return "Admin" == $user->name;
            // return "Admin" == $user->name || $car->owner == $user->id;
        });
        Gate::define("delete-user", function(User $user) {
            # Az admin törölhet csak a User-t
            return "Admin" == $user->name;
        });

        ## Az E-mail Blade-hez a time formatter function miatt
        Blade::directive('formatDuration', function ($minutes) {
            return "<?php echo TimeFormatHelper::formatDuration($minutes); ?>";
        });

    }
}
