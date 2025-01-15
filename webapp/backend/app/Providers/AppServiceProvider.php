<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Routing\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict();

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

    }
}
