<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\StringTools;

class StringServiceProvider extends ServiceProvider
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
        $this->app->bind(
            'App\Contracts\StringIF',
            'App\Services\StringTools'
        );
    }
}
