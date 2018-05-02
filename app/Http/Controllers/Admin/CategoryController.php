<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\CatConstModel;
use App\Models\Admin\CategoryModel;
use App\Models\Admin\CatModuleModel;
use Illuminate\Http\Request;
use App\Models\Admin\GeoModel;
use App\Http\Controllers\Controller;
use App\Http\Traits\AdminTools;
use App\Jobs\LogAction;
use App\Models\Admin\RoleModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
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

        $where = ['is_active' => 1];
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

            $cat->cat_level = $request->input('cat_level');

            if ($request->has('cat_parent') && $request->input('cat_level') != 1) {
                $cat->cat_parent = $request->input('cat_parent');
            } else {
                $cat->cat_parent = 0;
            }

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
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function showCatSon($id)
    {
        //
        $category = CategoryModel::where(['is_active' => 1, 'cat_parent' => $id])->get();

        $rst['code'] = 100;
        $rst['data'] = $category;

        return json_encode($rst);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function showCatModule($id)
    {
        //
        $category = CatModuleModel::where(['is_active' => 1, 'cat_id' => $id])->select('id', 'mod_name')->get();

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

    public function module(Request $request)
    {
        $this->pageTitle = '分类管理';
        $this->pageSubTitle = '管理系统分类模块';
        $this->pageModuleName = '分类管理';
        $this->pageModuleUrl = route('admin_category_list');
        $this->pageFuncName = '模块列表';

        $moduleTypeList = Config::get('constants.CAT_MODULE_TYPE_LIST');

        $firstCat = $request->input('search_top_cat', -1);
        $secCat = $request->input('search_sec_cat', -1);

        $topCat = CategoryModel::where('cat_level', 1)->get();
        $selTopCatId = ($topCat[0])->id;

        $where[] = ['is_active', 1];
        $whereIn = [];
        if ($secCat != -1) {
            $where[] = ['cat_id', $secCat];
        } elseif ($firstCat != -1) {
            $secCatList = CategoryModel::where(['is_active' => 1, 'cat_level' => 2, 'cat_parent' => $firstCat])->get();

            foreach ($secCatList as $loopCat) {
                array_push($whereIn, $loopCat->id);
            }
        }

        if ($firstCat != -1) {
            $selTopCatId = $firstCat;
        }

        $firstSecondCat = CategoryModel::where(['cat_level' => 2, 'cat_parent' => $selTopCatId])->get();

        if (count($whereIn) > 0) {
            $lists = CatModuleModel::where($where)->whereIn('cat_id', $whereIn)->with('category')->orderBy("show_order", "desc")->paginate($this->pageSize);
        } else {
            $lists = CatModuleModel::where($where)->with('category')->orderBy("show_order", "desc")->paginate($this->pageSize);
        }

        $lists = $lists->appends([
            'search_top_cat' => $firstCat,
            'search_sec_cat' => $secCat,
        ]);

        $moduleActive = true;
        return view('Admin/Category/catmodule', array_merge(compact('moduleActive', 'lists', 'topCat', 'firstSecondCat', 'moduleTypeList'), $this->getCommonParm()));
    }

    public function showModule($id)
    {
        $module = CatModuleModel::find($id);

        $rst['code'] = 100;
        $rst['data'] = $module;

        $catId = $module->cat_id;
        $nowCat = CategoryModel::find($catId);
        $nowTop = $nowCat->cat_parent;

        $secCats = CategoryModel::where(['is_active' => 1, 'cat_parent' => $nowTop])->get();

        $rst['sec_cats'] = $secCats;
        $rst['first_cat'] = $nowTop;

        return json_encode($rst);
    }

    public function editModule(Request $request, $id)
    {
        if ($request->isMethod('POST')) {

            $actionList = Config::get('constants.ACTION_LIST');

            if ($id == 0) {
                $module = new CatModuleModel();
                $act['action_detail'] = '创建模块';

                $act['action_type'] = $actionList[$this->moduleKey]['createCategory'];
            } else {
                $module = CatModuleModel::find($id);

                $act['action_detail'] = '更新模块';
                $act['action_type'] = $actionList[$this->moduleKey]['editCategory'];
            }

            $module->mod_name = trim($request->input('mod_name'));
            $module->mod_en_name = trim($request->input('mod_en_name'));
            $module->mod_type = $request->input('mod_type');

            $module->default_value = $request->input('default_value');
            $module->min_length = $request->input('min_length');
            $module->max_length = $request->input('max_length');
            $module->show_order = $request->input('show_order');
            $module->mod_dw = $request->input('mod_dw');

            $module->is_number = $request->input('is_number', '') == 'on' ? 1 : 0;
            $module->is_phone = $request->input('is_phone', '') == 'on' ? 1 : 0;
            $module->is_email = $request->input('is_email', '') == 'on' ? 1 : 0;
            $module->is_date = $request->input('is_date', '') == 'on' ? 1 : 0;
            $module->cat_id = $request->input('sec_cat');

            $module->save();

            $nowUserId = $this->getNowUser();

            $act['user_id'] = $nowUserId;
            $act['act_time'] = date('Y-m-d H:i:s');
            $act['is_admin'] = 1;

            $act['target_id'] = $module->id;

            $queueName = Config::get('constants.LOG_QUEUE_NAME');
            LogAction::dispatch($act)->onQueue($queueName);

            $rst['code'] = 100;
            return json_encode($rst);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroyModule(Request $request, $id)
    {
        //
        if ($request->ajax() && $request->isMethod('DELETE')) {
            CatModuleModel::where('id', $id)
                ->update(['is_active' => 0]);

            $actionList = Config::get('constants.ACTION_LIST');
            $nowUserId = $this->getNowUser();

            $act['user_id'] = $nowUserId;
            $act['action_type'] = $actionList[$this->moduleKey]['deleteModule'];
            $act['act_time'] = date('Y-m-d H:i:s');
            $act['is_admin'] = 1;
            $act['action_detail'] = '删除分类模块';
            $act['target_id'] = $id;

            $queueName = Config::get('constants.LOG_QUEUE_NAME');
            LogAction::dispatch($act)->onQueue($queueName);

            $rst['code'] = 100;
            return json_encode($rst);
        }
    }

    public function editConst(Request $request, $id)
    {
        if ($request->isMethod('POST')) {

            $actionList = Config::get('constants.ACTION_LIST');

            if ($id == 0) {
                $const = new CatConstModel();
                $act['action_detail'] = '创建常量';

                $act['action_type'] = $actionList[$this->moduleKey]['createConst'];
            } else {
                $const = CatConstModel::find($id);

                $act['action_detail'] = '更新常量';
                $act['action_type'] = $actionList[$this->moduleKey]['editConst'];
            }

            $const->mod_id = $request->input('cat_module');

            $const->const_text = trim($request->input('const_text'));
            $const->const_val = trim($request->input('const_val'));
            $const->show_order = $request->input('show_order');

            $const->save();

            $nowUserId = $this->getNowUser();

            $act['user_id'] = $nowUserId;
            $act['act_time'] = date('Y-m-d H:i:s');
            $act['is_admin'] = 1;

            $act['target_id'] = $const->id;

            $queueName = Config::get('constants.LOG_QUEUE_NAME');
            LogAction::dispatch($act)->onQueue($queueName);

            $rst['code'] = 100;
            return json_encode($rst);
        }
    }

    public function showCatConst(Request $request, $id)
    {
        $const = CatConstModel::with('module:id,mod_name')->with('module.category.moduleList:id,cat_id,mod_name')->with('module.category.parent:id')->find($id);

        $rst['code'] = 100;
        $rst['data'] = $const;

        $nowTop = $const->module->category->parent->id;

        $secCats = CategoryModel::where(['is_active' => 1, 'cat_parent' => $nowTop])->select('id', 'cat_name')->get();

        $rst['sec_cats'] = $secCats;
        $rst['first_cat'] = $const->module->category->parent->id;

        return json_encode($rst);
    }

    public function destroyConst(Request $request, $id)
    {
        //
        if ($request->ajax() && $request->isMethod('DELETE')) {
            CatConstModel::destroy($id);

            $actionList = Config::get('constants.ACTION_LIST');
            $nowUserId = $this->getNowUser();

            $act['user_id'] = $nowUserId;
            $act['action_type'] = $actionList[$this->moduleKey]['deleteConst'];
            $act['act_time'] = date('Y-m-d H:i:s');
            $act['is_admin'] = 1;
            $act['action_detail'] = '删除分类常量';
            $act['target_id'] = $id;

            $queueName = Config::get('constants.LOG_QUEUE_NAME');
            LogAction::dispatch($act)->onQueue($queueName);

            $rst['code'] = 100;
            return json_encode($rst);
        }
    }

    public function consts(Request $request)
    {
        $this->pageTitle = '分类管理';
        $this->pageSubTitle = '管理分类模块的常量数据';
        $this->pageModuleName = '分类管理';
        $this->pageModuleUrl = route('admin_category_list');
        $this->pageFuncName = '常量列表';

        $topCat = CategoryModel::where('cat_level', 1)->get();

        $firstCat = $request->input('search_top_cat', -1);
        $secCat = $request->input('search_sec_cat', -1);
        $module = $request->input('search_cat_module', -1);

        $where = [];
        $whereIn = [];
        $moduleList = [];

        $secondCatParent = $topCat[0]->id;

        if ($module != -1) {
            $moduleList[] = $module;
        } else if ($firstCat != -1) {
            $secondCatParent = $firstCat;

            $secCatList = [];

            if ($secCat != -1) {
                // 当前二级分类下的模块
                $secCatList[] = $secCat;
            } else {
                // 当前一级分类下的所有二级分类.
                $sonList = CategoryModel::where(['is_active' => 1, 'cat_level' => 2, 'cat_parent' => $firstCat])->get();
                foreach ($sonList as $loopCat) {
                    $secCatList[] = $loopCat->id;
                }
            }
//            print_r($secCatList);
            // 查各个二级分类下所有的模块
            foreach ($secCatList as $loopCat) {
                $loopModuleList = CatModuleModel::where(['is_active' => 1, 'cat_id' => $loopCat])->select('id')->get();

                foreach ($loopModuleList as $loopMod) {
                    $moduleList[] = $loopMod->id;
                }
            }
        }

        $firstSecondCat = CategoryModel::where(['cat_level' => 2, 'cat_parent' => $secondCatParent])->get();

        $searchModCat = $firstSecondCat[0]->id;
        if ($secCat != -1) {
            $searchModCat = $secCat;
        }

        $defaultCatModule = CatModuleModel::where('cat_id', $searchModCat)->get();

//        DB::connection()->enableQueryLog();
//
//        print_r($moduleList);
        if (count($moduleList) <= 0 && ($firstCat == -1 && $secCat == -1 && $module == -1)) {
            $lists = CatConstModel::with(['module', 'module.category', 'module.category.parent'])->paginate($this->pageSize);
        } else {
            $lists = CatConstModel::whereIn('mod_id', $moduleList)->with(['module', 'module.category', 'module.category.parent'])->paginate($this->pageSize);
        }
//        $log = DB::getQueryLog();
//        print_r($log);

        $lists = $lists->appends([
            'search_top_cat' => $firstCat,
            'search_sec_cat' => $secCat,
            'search_cat_module' => $module,
        ]);

        $constActive = true;
        return view('Admin/Category/consts', array_merge(compact('constActive', 'lists', 'topCat', 'firstSecondCat', 'defaultCatModule'), $this->getCommonParm()));
    }

    public function showCatForm(Request $request, $id)
    {
//        DB::connection()->enableQueryLog();

        $moduleList = CatModuleModel::where("cat_id", $id)->with(["constant" => function ($query) {
            $query->orderBy('show_order', 'desc');
        }])->orderBy("show_order", 'desc')->get();

//        $log = DB::getQueryLog();
//        print_r($log);

        return view('Admin/Category/catform', compact('moduleList'));
    }
}
