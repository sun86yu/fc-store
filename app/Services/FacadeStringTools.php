<?php
/**
 * Created by my-laravel.
 * Func: ArrayTools.php
 * User: sunyu
 * Date: 2018/4/4
 * Time: 下午1:17
 */

namespace App\Services;

use Illuminate\Support\Facades\Facade;


class FacadeStringTools extends Facade
{
    /**
     * 获取组件的注册名称。
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'App\Contracts\StringIF';
    }

    public static function extendLen()
    {
        return 5;
    }
}