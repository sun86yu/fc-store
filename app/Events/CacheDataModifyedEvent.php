<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class CacheDataModifyedEvent
{
    use SerializesModels;

    public $cacheKey;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($key)
    {
        //
        $this->cacheKey = $key;
    }
}
