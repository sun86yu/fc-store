<?php

namespace App\Jobs;

use App\Models\Admin\ProductModel;
use Elasticsearch\ClientBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class ElasticSearchUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public static $OPER_ADD = 1;
    public static $OPER_UP = 2;
    public static $OPER_DEL = 3;
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

    public $good;
    public $oper;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ProductModel $good, $oper)
    {
        //
        $this->good = $good;
        $this->oper = $oper;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // TODO:更新 ES.根据操作不同,执行不同功能
        $hosts = [
            Config::get('database.elasticsearch.host') . ':' . Config::get('database.elasticsearch.port'),
        ];
        $client = ClientBuilder::create()->setHosts($hosts)->build();
        switch ($this->oper) {
            case self::$OPER_ADD:
                $params = [
                    'index' => Config::get('database.elasticsearch.index'),
                    'type' => Config::get('database.elasticsearch.type'),
                    'id' => $this->good->id,
                    'body' => [
                        'category' => $this->good->cat_id,
                        'price' => $this->good->price,
                        'remain_cnt' => $this->good->remain_cnt,
                        'saled_cnt' => $this->good->saled_cnt,
                        'pro_img' => $this->good->pro_img,
                        'info' => $this->good->info,
                        'pro_name' => $this->good->pro_name,
                        'create_time' => strtotime($this->good->create_time),
                    ]
                ];

                $response = $client->index($params);
                break;
            case self::$OPER_UP:
                $params = [
                    'index' => Config::get('database.elasticsearch.index'),
                    'type' => Config::get('database.elasticsearch.type'),
                    'id' => $this->good->id,
                    'body' => [
                        'doc' => [
                            'category' => $this->good->cat_id,
                            'price' => $this->good->price,
                            'remain_cnt' => $this->good->remain_cnt,
                            'saled_cnt' => $this->good->saled_cnt,
                            'pro_img' => $this->good->pro_img,
                            'info' => $this->good->info,
                            'pro_name' => $this->good->pro_name,
                            'create_time' => strtotime($this->good->create_time),
                        ]
                    ]
                ];

                $response = $client->update($params);
                break;
            case self::$OPER_DEL:
                $params = [
                    'index' => Config::get('database.elasticsearch.index'),
                    'type' => Config::get('database.elasticsearch.type'),
                    'id' => $this->good->id,
                ];

                $response = $client->delete($params);
                break;
        }
    }
}
