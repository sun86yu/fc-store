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
            'createUser' => 11,
            'editUser' => 12,
            'deleteUser' => 13,
            'createRole' => 21,
            'editRole' => 22,
            'deleteRole' => 23,
        ],
        'article' => [
            'createArticle' => 31,
            'editArticle' => 32,
            'deleteArticle' => 33,
        ],
        'user' => [
            'member' => 41,
        ],
        'product' => [
            'createProduct' => 51,
            'editProduct' => 52,
            'deleteProduct' => 53,
        ],
        'category' => [
            'createCategory' => 61,
            'editCategory' => 62,
            'deleteCategory' => 63,
            'createModule' => 64,
            'editModule' => 65,
            'deleteModule' => 66,
            'createConst' => 67,
            'editConst' => 68,
            'deleteConst' => 69,
        ],
        'geo' => [
            'createGeo' => 71,
            'editGeo' => 72,
            'deleteGeo' => 73,
        ],
        'log' => [
            'systemlog' => 81,
        ],
    ],
    'LOG_QUEUE_NAME' => 'sys_logs',
    'DEFAULT_PWD' => '123456',
    'CAT_MODULE_TYPE_LIST' => [
        1 => '文本框',
        2 => 'textarea',
        3 => '下拉框',
        4 => '单选框',
        5 => '复选框',
        6 => '时间选择框'
    ],
];