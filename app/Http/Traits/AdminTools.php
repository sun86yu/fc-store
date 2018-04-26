<?php
/**
 * Created by PhpStorm.
 * User: sunyu
 * Date: 2018/3/9
 * Time: 上午1:11
 */

namespace App\Http\Traits;

trait AdminTools
{
    public function getNowUser()
    {
        return 1;
    }

    public $pageSize = 10;

    public $pageTitle = '';
    public $pageSubTitle = '';
    public $pageModuleName = '';
    public $pageModuleUrl = '';
    public $pageFuncName = '';

    public $moduleKey;

    public function getCommonParm()
    {
        $pageTitle = $this->pageTitle;
        $subTitle = $this->pageSubTitle;
        $moduleName = $this->pageModuleName;
        $moduleUrl = $this->pageModuleUrl;
        $funcName = $this->pageFuncName;

        return compact('pageTitle', 'subTitle', 'moduleName', 'moduleUrl', 'funcName');
    }
}