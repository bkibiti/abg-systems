<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

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
        // Existing rows in model_has_roles / model_has_permissions store the
        // pre-upgrade class name, so keep reading and writing 'App\User'.
        Relation::morphMap([
            'App\User' => User::class,
        ]);
    }
}
