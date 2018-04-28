<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ShareDataProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // 所有视图中共享数据
        View::share('adminActive', false);
        View::share('roleActive', false);
        View::share('userActive', false);
        View::share('identyActive', false);
        View::share('productActive', false);
        View::share('productAddActive', false);
        View::share('orderActive', false);
        View::share('cateActive', false);
        View::share('moduleActive', false);
        View::share('constActive', false);
        View::share('geoActive', false);
        View::share('logActive', false);
        View::share('newsActive', false);
        View::share('newAddActive', false);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
