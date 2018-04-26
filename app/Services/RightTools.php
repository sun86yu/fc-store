<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

class RightTools
{

    public function getRightList($rightValue = 0)
    {
        $moduleLists = Config::get('constants.MODULE_LIST');

        $rightBin = decbin($rightValue);
        $rightBin = str_pad($rightBin, count($moduleLists), '0', STR_PAD_LEFT);

        $idx = 0;
        $right = [];
        foreach ($moduleLists as $key => $name) {
            if ($rightBin[$idx] == 1) {
                $right[$key] = 1;
            } else {
                $right[$key] = 0;
            }
            $idx++;
        }
        return $right;
    }

    public function getRightValue($rightList = [])
    {

        $moduleLists = Config::get('constants.MODULE_LIST');

        $rightStr = str_pad('', count($moduleLists), '0', STR_PAD_LEFT);
        $idx = 0;
        foreach ($moduleLists as $key => $module) {
            if (array_key_exists('role_right_' . $key, $rightList) && $rightList['role_right_' . $key] == 'on') {
                $rightStr[$idx] = 1;
            }
            $idx++;
        }
        return bindec($rightStr);
    }
}