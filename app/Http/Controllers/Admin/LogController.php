<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\LogModel;
use Illuminate\Http\Request;
use App\Http\Traits\AdminTools;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as RequestFacade;

class LogController extends Controller
{
    use AdminTools;

    //
    public function index(Request $request)
    {
        $this->pageTitle = '日志列表';
        $this->pageSubTitle = '系统内所有日志查询';
        $this->pageModuleName = '日志列表';
        $this->pageModuleUrl = route('admin_logs_list');
        $this->pageFuncName = '日志查询';

        $moduleLists = Config::get('constants.MODULE_LIST');
        $actionList = Config::get('constants.ACTION_LIST');

        $logActive = true;

        $where = [];
        $whereIn = [];
        $where['is_admin'] = 1;

        $startDate = date('Y-m-d 00:00:00', strtotime('-6 days'));
        $endDate = date('Y-m-d 23:59:59');

        if ($request->isMethod('POST')) {
            $data = $request->post();

            if (RequestFacade::has('search_is_admin')) {
                $where['is_admin'] = $data['search_is_admin'];
            }

            if (RequestFacade::has('search_time_range')) {
                $dateRange = $data['search_time_range'];

                $dateList = explode('-', $dateRange);
                if (count($dateList) == 2) {
                    $startDate = trim($dateList[0]);
                    $endDate = trim($dateList[1]);

                    $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
                    $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
                }
            }

            if (RequestFacade::has('search_user_id') && $data['search_user_id'] > 0) {
                $where['user_id'] = $data['search_user_id'];
            }

            if (RequestFacade::has('search_module_id') && $data['search_module_id'] != -1) {

                foreach ($actionList as $loopModu => $loopAclist) {
                    if ($loopModu == $data['search_module_id']) {
                        foreach ($loopAclist as $loopKey => $loopVal) {
                            array_push($whereIn, $loopVal);
                        }
                    }
                }
            }
        }

//        DB::connection()->enableQueryLog();

        if (array_key_exists('is_admin', $where) && $where['is_admin'] == 1) {
            if (count($whereIn) > 0) {
                $lists = LogModel::where($where)->whereIn('action_type', $whereIn)->whereBetween('act_time', [$startDate, $endDate])->orderBy('act_time', 'desc')->with('admin')->paginate($this->pageSize);
            } else {
                $lists = LogModel::where($where)->whereBetween('act_time', [$startDate, $endDate])->orderBy('act_time', 'desc')->with('admin')->paginate($this->pageSize);
            }
        } else {
            if (count($whereIn) > 0) {
                $lists = LogModel::where($where)->whereIn('action_type', $whereIn)->whereBetween('act_time', [$startDate, $endDate])->orderBy('act_time', 'desc')->paginate($this->pageSize);
            } else {
                $lists = LogModel::where($where)->whereBetween('act_time', [$startDate, $endDate])->orderBy('act_time', 'desc')->paginate($this->pageSize);
            }
        }

//        $log = DB::getQueryLog();
//        print_r($log);
//        print_r($whereIn);
//        print_r($lists);

        return view('Admin/Log/logs', array_merge(compact('logActive', 'lists', 'moduleLists', 'actionList'), $this->getCommonParm()));
    }
}
