<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\GeoModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\AdminTools;
use App\Jobs\LogAction;
use Illuminate\Support\Facades\Config;

class GeoController extends Controller
{
    use AdminTools;

    public function __construct()
    {
        $this->moduleKey = 'geo';
    }

    //
    public function index(Request $request)
    {
        $geoActive = true;

        $this->pageTitle = '系统地区列表';
        $this->pageSubTitle = '管理系统地区信息';
        $this->pageModuleName = '地区管理';
        $this->pageModuleUrl = route('admin_geo_list');
        $this->pageFuncName = '地区列表';

        $where = [];

        if ($request->input('top_geo', -1) != -1) {
            $where[] = ['id', 'like', $request->input('top_geo') . '%'];
        }

        if ($request->input('geo_level', -1) != -1) {
            $where[] = ['geo_level', $request->input('geo_level')];
        }

        $lists = GeoModel::where($where)->paginate($this->pageSize);
        $topGeo = GeoModel::where('geo_level', 1)->get();

        $lists = $lists->appends([
            'top_geo' => $request->input('top_geo'),
            'geo_level' => $request->input('geo_level'),
        ]);

        return view('Admin/Geo/geos', array_merge(compact('geoActive', 'lists', 'topGeo'), $this->getCommonParm()));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
        if ($request->isMethod('POST')) {

            $actionList = Config::get('constants.ACTION_LIST');

            $geo = GeoModel::find($id);

            $act['action_detail'] = '更新地区';
            $act['action_type'] = $actionList[$this->moduleKey]['editGeo'];

            $geo->geo_name = trim($request->input('geo_name'));
            $geo->is_active = $request->input('is_active');

            $geo->save();

            $nowUserId = $this->getNowUser();

            $act['user_id'] = $nowUserId;
            $act['act_time'] = date('Y-m-d H:i:s');
            $act['is_admin'] = 1;

            $act['target_id'] = $geo->id;

            $queueName = Config::get('constants.LOG_QUEUE_NAME');
            LogAction::dispatch($act)->onQueue($queueName);

            $rst['code'] = 100;
            return json_encode($rst);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $geo = GeoModel::find($id);

        $rst['code'] = 100;
        $rst['data'] = $geo;

        return json_encode($rst);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        if ($request->ajax() && $request->isMethod('DELETE')) {
            GeoModel::where('id', $id)
                ->update(['is_active' => 0]);

            $actionList = Config::get('constants.ACTION_LIST');
            $nowUserId = $this->getNowUser();

            $act['user_id'] = $nowUserId;
            $act['action_type'] = $actionList[$this->moduleKey]['deleteGeo'];
            $act['act_time'] = date('Y-m-d H:i:s');
            $act['is_admin'] = 1;
            $act['action_detail'] = '删除地区';
            $act['target_id'] = $id;

            $queueName = Config::get('constants.LOG_QUEUE_NAME');
            LogAction::dispatch($act)->onQueue($queueName);

            $rst['code'] = 100;
            return json_encode($rst);
        }
    }
}
