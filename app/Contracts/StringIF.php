<?php

namespace App\Contracts;
/**
 * Created by my-laravel.
 * Func: StringIF.php
 * User: sunyu
 * Date: 2018/4/4
 * Time: 上午11:08
 */
interface StringIF
{
    /**
     * Get the size of the string.
     *
     * @param  string $str
     * @return int
     */
    public function strlen($str = null);

    /**
     * print string
     *
     * @param  string $str
     * @return mixed
     */
    public function echo($str = null);
}