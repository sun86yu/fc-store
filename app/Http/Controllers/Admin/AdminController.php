<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\AdminTools;
use App\Jobs\LogAction;
use App\Models\Admin\AdminModel;
use App\Models\Admin\RoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Services\RightTools;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    use AdminTools;

    public function __construct()
    {
        $this->moduleKey = 'admin';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $this->pageTitle = '管理员列表';
        $this->pageSubTitle = '管理后台所有管理员';
        $this->pageModuleName = '管理员';
        $this->pageModuleUrl = route('admin_user_list');
        $this->pageFuncName = '管理员列表';

        $where = [];

        if ($request->input('search_status', -1) != -1) {
            $where['status'] = $request->input('search_status');
        }

        if ($request->input('search_role_id', -1) != -1) {
            $where['role_id'] = $request->input('search_role_id');
        }

        if ($request->input('search_name', "") != "") {
            $where[] = ['nick_name', 'like', '%' . $request->input('search_name') . '%'];
        }

//            $request->flashOnly('search_status', 'search_role_id', 'search_name');
        $moduleLists = Config::get('constants.MODULE_LIST');

//        DB::connection()->enableQueryLog();
        $lists = AdminModel::where($where)->with('role')->orderBy('create_time', 'desc')->paginate($this->pageSize);

//        $log = DB::getQueryLog();
//        print_r($log);
        $roleList = RoleModel::all();

        $lists = $lists->appends([
            'search_status' => $request->input('search_status', -1),
            'search_role_id' => $request->input('search_role_id', -1),
            'search_name' => $request->input('search_name')
        ]);

        $adminActive = true;
        return view('Admin/User/admins', array_merge(compact('adminActive', 'lists', 'moduleLists', 'roleList'), $this->getCommonParm()));
    }

    public function role()
    {
        //
        $this->pageTitle = '管理员角色列表';
        $this->pageSubTitle = '管理后台所有管理员角色';
        $this->pageModuleName = '管理员';
        $this->pageModuleUrl = route('admin_user_list');
        $this->pageFuncName = '角色列表';

        $lists = RoleModel::with('user')->paginate($this->pageSize);

        $moduleLists = Config::get('constants.MODULE_LIST');

        $roleActive = true;
        return view('Admin/User/admin_role', array_merge(compact('roleActive', 'lists', 'moduleLists'), $this->getCommonParm()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function adminfunc(Request $request, $id)
    {
        //
        $rst = [];

        $nowUserId = $this->getNowUser();
        $actionList = Config::get('constants.ACTION_LIST');
        $queueName = Config::get('constants.LOG_QUEUE_NAME');

        if ($request->ajax() && $request->isMethod('GET')) {
            $admin = AdminModel::with('role')->find($id);

            $rst['code'] = 100;
            $rst['data'] = $admin;
        } else if ($request->ajax() && $request->isMethod('DELETE')) {
            AdminModel::where('id', $id)
                ->update(['status' => 0]);

            $act['user_id'] = $nowUserId;
            $act['action_type'] = $actionList[$this->moduleKey]['deleteUser'];
            $act['act_time'] = date('Y-m-d H:i:s');
            $act['is_admin'] = 1;
            $act['action_detail'] = '禁用管理员';
            $act['target_id'] = $id;

            LogAction::dispatch($act)->onQueue($queueName);

            $rst['code'] = 100;
        } else if ($request->isMethod('POST')) {

            // 名称长度检测
            $input = [
                'admin_name' => $request->input('admin_name'),
                'admin_email' => $request->input('admin_email'),
                'nick_name' => $request->input('nick_name'),
            ];
            $rules = [
                'admin_name' => 'required|max:45',
                'admin_email' => 'required|max:200',
                'nick_name' => 'required|max:45',
            ];
            $messages = [
                'required' => ':attribute 值必须填写.',
                'max' => ':attribute 长度不能超过 :max.',
            ];

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                $rst['code'] = 0;
                $rst['data']['error'] = $validator->errors()->first();

                return json_encode($rst);
            }

            $input['status'] = $request->input('status', 0);
            $input['role_id'] = $request->input('role_id');

            if (trim($request->input('admin_pwd', "")) != "") {
                if ($request->input('admin_pwd') != $request->input('admin_pwd2')) {
                    $rst['code'] = 0;
                    $rst['data']['error'] = '重复密码必须和密码相同!';

                    return json_encode($rst);
                }

                $input['admin_pwd'] = md5($request->input('admin_pwd'));
            }
            $input['create_time'] = date('Y-m-d H:i:s');

            $act['user_id'] = $nowUserId;
            $act['act_time'] = date('Y-m-d H:i:s');
            $act['is_admin'] = 1;

            if ($request->input('admin_id', 0) > 0) {
                AdminModel::where('id', $request->input('admin_id'))
                    ->update($input);

                $act['action_type'] = $actionList[$this->moduleKey]['editUser'];
                $act['action_detail'] = '编辑管理员信息';
                $act['target_id'] = $request->input('admin_id');

            } else {
                if (!array_key_exists('admin_pwd', $input)) {
                    $input['admin_pwd'] = md5(Config::get('constants.DEFAULT_PWD'));
                }
                $newAdmin = AdminModel::create($input);

                $act['action_type'] = $actionList[$this->moduleKey]['createUser'];
                $act['action_detail'] = '创建新管理员';
                $act['target_id'] = $newAdmin->id;

            }

            LogAction::dispatch($act)->onQueue($queueName);

            $rst['code'] = 100;
            return json_encode($rst);
        }

        return json_encode($rst);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function rolefunc(Request $request, RightTools $rightTools, $id)
    {
        //
        $rst = [];

        $nowUserId = $this->getNowUser();
        $actionList = Config::get('constants.ACTION_LIST');
        $queueName = Config::get('constants.LOG_QUEUE_NAME');

        if ($request->ajax() && $request->isMethod('GET')) {

            $role = RoleModel::find($id);

            $rst['code'] = 100;
            $rst['data']['name'] = $role->role_name;
            $rst['data']['right'] = $rightTools->getRightList($role->role_right);
        } else if ($request->isMethod('POST')) {
            // 名称长度检测
            $input = [
                'role_name' => $request->input('role_name'),
            ];
            $rules = [
                'role_name' => 'required|max:45',
            ];
            $messages = [
                'required' => ':attribute 值必须填写.',
                'max' => ':attribute 长度不能超过 :max.',
            ];

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                $rst['code'] = 0;
                $rst['data']['error'] = $validator->errors()->first('role_name');

                return json_encode($rst);
            }


            $act['user_id'] = $nowUserId;
            $act['act_time'] = date('Y-m-d H:i:s');
            $act['is_admin'] = 1;

            if ($request->input('role_id') > 0) {
                RoleModel::where('id', $request->input('role_id'))
                    ->update(['role_name' => $request->input('role_name'), 'role_right' => $rightTools->getRightValue($request->post())]);

                $act['action_type'] = $actionList[$this->moduleKey]['editRole'];
                $act['action_detail'] = '编辑角色';
                $act['target_id'] = $request->input('role_id');
            } else {
                $item = new RoleModel();

                $item->role_name = $request->input('role_name');
                $item->role_right = $rightTools->getRightValue($request->post());

                $item->save();

                $act['action_type'] = $actionList[$this->moduleKey]['createRole'];
                $act['action_detail'] = '新建角色';
                $act['target_id'] = $item->id;
            }

            $rst['code'] = 100;
            LogAction::dispatch($act)->onQueue($queueName);

            return json_encode($rst);
        } else if ($request->isMethod('DELETE')) {
            RoleModel::destroy($id);

            $act['user_id'] = $nowUserId;
            $act['action_type'] = $actionList[$this->moduleKey]['deleteRole'];
            $act['act_time'] = date('Y-m-d H:i:s');
            $act['is_admin'] = 1;
            $act['action_detail'] = '删除角色';
            $act['target_id'] = $id;

            LogAction::dispatch($act)->onQueue($queueName);

            $rst['code'] = 100;
        }

        return json_encode($rst);
    }
}
