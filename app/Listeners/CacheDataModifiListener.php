<?php

namespace App\Listeners;

use App\Events\CacheDataModifyedEvent;
use Illuminate\Support\Facades\Cache;

class CacheDataModifiListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CacheDataModifyedEvent $event
     * @return void
     */
    public function handle(CacheDataModifyedEvent $event)
    {
        // 清除缓存中key对应的数据
        Cache::forget($event->cacheKey);
        print_r($event->cacheKey);
        exit;
    }
}
