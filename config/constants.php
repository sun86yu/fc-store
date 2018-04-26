<?php
/**
 * Created by my-laravel.
 * Func: constants.php
 * User: sunyu
 * Date: 2018/4/25
 * Time: 下午3:11
 */
return [
    'MODULE_LIST' => ['admin' => '管理员权限', 'article' => '文章管理', 'user' => '用户管理', 'product' => '商品管理', 'category' => '类别管理', 'geo' => '地区管理', 'log' => '日志管理'],
    'ACTION_LIST' => [
        'admin' => [
            'create' => 11,
            'edit' => 12,
            'delete' => 13,
        ],
        'role' => [
            'create' => 21,
            'edit' => 22,
            'delete' => 23,
        ],
    ],
    'LOG_QUEUE_NAME' => 'sys_logs',
];