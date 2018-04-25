<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImagesPress implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 任务可以尝试的最大次数。如果不指定，任务会无限次尝试。
     *
     * @var int
     */
    public $tries = 5;

    /**
     * 超时时间。
     *
     * @var int
     */
    public $timeout = 120;

    protected $userId;

    public function __construct($uid)
    {
        $this->userId = $uid;
    }

    public function handle()
    {
        //
        for ($i = 0; $i < 10; $i++) {
            echo '<br />处理用户 ' . $this->userId . ' 的图片!<br />';
            sleep(1);
        }

    }

    /**
     * 执行失败的任务。
     */
    public function failed(Exception $exception)
    {
        // 给用户发送失败的通知等等...
    }
}
