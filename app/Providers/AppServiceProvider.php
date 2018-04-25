<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessed;
use Laravel\Horizon\Horizon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Horizon::auth(function ($request) {
            // 判断权限的逻辑
            // return true / false;
            return true;
        });
        Queue::after(function (JobProcessed $event) {
            echo $event->connectionName . '<br />';
            echo $event->job->getName() . '<br />';
            echo '事件执行完了!<br />';
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
