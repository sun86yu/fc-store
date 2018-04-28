<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\CatConstModel;
use App\Models\Admin\CategoryModel;
use Illuminate\Http\Request;
use App\Models\Admin\GeoModel;
use App\Http\Controllers\Controller;
use App\Http\Traits\AdminTools;
use App\Jobs\LogAction;
use App\Models\Admin\RoleModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request as RequestFacade;

class CategoryController extends Controller
{
    use AdminTools;

    public function __construct()
    {
        $this->moduleKey = 'category';
    }

    //
    public function index(Request $request)
    {
        $cateActive = true;

        $this->pageTitle = '分类管理';
        $this->pageSubTitle = '管理系统分类信息';
        $this->pageModuleName = '分类管理';
        $this->pageModuleUrl = route('admin_category_list');
        $this->pageFuncName = '分类列表';

        $where = [];
        if ($request->input('search_top_cat', -1) != -1) {
            $where[] = ['cat_parent', $request->input('search_top_cat')];
        }

        if ($request->input('search_cat_level', -1) != -1) {
            $where[] = ['cat_level', $request->input('search_cat_level')];
        }

        $lists = CategoryModel::where($where)->with('parent')->paginate($this->pageSize);
        $topCat = CategoryModel::where('cat_level', 1)->get();

        $lists = $lists->appends([
            'search_top_cat' => $request->input('search_top_cat'),
            'cat_level' => $request->input('cat_level'),
        ]);

        return view('Admin/Category/cats', array_merge(compact('cateActive', 'lists', 'topCat'), $this->getCommonParm()));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editCat(Request $request, $id)
    {
        //
        if ($request->isMethod('POST')) {

            $actionList = Config::get('constants.ACTION_LIST');

            if ($id == 0) {
                $cat = new CategoryModel();
                $act['action_detail'] = '创建分类';
                $act['action_type'] = $actionList[$this->moduleKey]['createCategory'];
            } else {
                $cat = CategoryModel::find($id);
                $act['action_detail'] = '更新分类';
                $act['action_type'] = $actionList[$this->moduleKey]['editCategory'];
            }

            $cat->cat_name = trim($request->input('cat_name'));
            $cat->is_active = $request->input('is_active');

            if ($request->has('cat_parent')) {
                $cat->cat_parent = $request->input('cat_parent');
            } else {
                $cat->cat_parent = 0;
            }
            $cat->cat_level = $request->input('cat_level');

            $cat->save();

            $nowUserId = $this->getNowUser();

            $act['user_id'] = $nowUserId;
            $act['act_time'] = date('Y-m-d H:i:s');
            $act['is_admin'] = 1;

            $act['target_id'] = $cat->id;

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
    public function showCat($id)
    {
        //
        $category = CategoryModel::find($id);

        $rst['code'] = 100;
        $rst['data'] = $category;

        return json_encode($rst);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroyCat(Request $request, $id)
    {
        //
        if ($request->ajax() && $request->isMethod('DELETE')) {
            CategoryModel::where('id', $id)
                ->update(['is_active' => 0]);

            $actionList = Config::get('constants.ACTION_LIST');
            $nowUserId = $this->getNowUser();

            $act['user_id'] = $nowUserId;
            $act['action_type'] = $actionList[$this->moduleKey]['deleteCategory'];
            $act['act_time'] = date('Y-m-d H:i:s');
            $act['is_admin'] = 1;
            $act['action_detail'] = '删除分类';
            $act['target_id'] = $id;

            $queueName = Config::get('constants.LOG_QUEUE_NAME');
            LogAction::dispatch($act)->onQueue($queueName);

            $rst['code'] = 100;
            return json_encode($rst);
        }
    }


    public function module()
    {
        $moduleActive = true;
        return view('Admin/Category/catmodule', compact('moduleActive'));
    }

    public function consts()
    {
        $this->pageTitle = '分类管理';
        $this->pageSubTitle = '管理分类模块的常量数据';
        $this->pageModuleName = '分类管理';
        $this->pageModuleUrl = route('admin_category_list');
        $this->pageFuncName = '常量列表';

        $lists = CatConstModel::with('module')->paginate($this->pageSize);

        $constActive = true;
        return view('Admin/Category/consts', array_merge(compact('constActive', 'lists'), $this->getCommonParm()));
    }
}
