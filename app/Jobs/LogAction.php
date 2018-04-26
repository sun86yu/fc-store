<?php
/**
 * Created by my-laravel.
 * Func: admin.php 异步记录系统日志
 * User: sunyu
 * Date: 2018/4/26
 * Time: 下午14:18
 */

namespace App\Jobs;

use App\Models\Admin\LogModel;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LogAction implements ShouldQueue
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
    public $timeout = 10;

    public $userId;
    public $actType;
    public $actTime;
    public $targetId;
    public $actDetail;
    public $isAdmin;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($logInfo)
    {
        //
        $this->userId = $logInfo['user_id'];
        $this->actDetail = $logInfo['action_detail'];
        $this->actTime = $logInfo['act_time'];
        $this->actType = $logInfo['action_type'];
        $this->targetId = $logInfo['target_id'];
        $this->isAdmin = $logInfo['is_admin'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $input['user_id'] = $this->userId;
        $input['action_type'] = $this->actType;
        $input['act_time'] = $this->actTime;
        $input['is_admin'] = $this->isAdmin;
        $input['action_detail'] = $this->actDetail;
        $input['target_id'] = $this->targetId;

        LogModel::create($input);
    }
}
