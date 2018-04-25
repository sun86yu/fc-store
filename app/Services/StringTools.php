<?php

namespace App\Services;

use App\Contracts\StringIF;

class StringTools implements StringIF
{

    public function echo($str = null)
    {
        return 'StringTools Print: ' . $str;
    }

    public function strlen($str = null)
    {
        return strlen($str);
    }
}