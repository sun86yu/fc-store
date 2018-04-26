<?php

namespace App\Providers;

use App\Services\RightTools;
use Illuminate\Support\ServiceProvider;

class RoleRightProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton('RightTools', function ($app) {
            return new RightTools();
        });
    }
}
