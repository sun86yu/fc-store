<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\ProductModel;
use Illuminate\Http\Request;
use App\Http\Traits\AdminTools;
use Illuminate\Support\Facades\Config;
use App\Models\Admin\CategoryModel;
use App\Jobs\LogAction;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    use AdminTools;

    public function __construct()
    {
        $this->moduleKey = 'product';
    }

    //
    public function index()
    {
        $this->pageTitle = '商品管理';
        $this->pageSubTitle = '管理系统商品信息';
        $this->pageModuleName = '商品管理';
        $this->pageModuleUrl = route('admin_product_list');
        $this->pageFuncName = '商品列表';

        $where = [];
        $lists = ProductModel::where($where)->with('category')->paginate($this->pageSize);

        $productActive = true;
        return view('Admin/Product/products', array_merge(compact('productActive', 'lists'), $this->getCommonParm()));
    }

    public function create()
    {
        $this->pageTitle = '商品管理';
        $this->pageSubTitle = '新建商品';
        $this->pageModuleName = '商品管理';
        $this->pageModuleUrl = route('admin_product_list');
        $this->pageFuncName = '添加商品';

        $productAddActive = true;

        $topCat = CategoryModel::where('cat_level', 1)->get();

        $secondCatParent = $topCat[0]->id;
        $firstSecondCat = CategoryModel::where(['cat_level' => 2, 'cat_parent' => $secondCatParent])->get();

        return view('Admin/Product/productadd', array_merge(compact('productAddActive', 'topCat', 'firstSecondCat'), $this->getCommonParm()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        //
        $path = '';
        if ($request->ajax() && $request->isMethod('POST')) {
            $file = $request->file('files');
            if (is_array($file)) {
                $file = $file[0];
            }
            $dirName = date('Y-m-d');

            //        Storage::put($dirName, $file);
            $path = $file->store($dirName);
        }

        return json_encode([
            'code' => 100,
            'files' => [['name' => '/storage/' . $path]]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroyProduct(Request $request, $id)
    {
        //
        if ($request->ajax() && $request->isMethod('DELETE')) {
            ProductModel::where('id', $id)
                ->update(['status' => 0]);

            $actionList = Config::get('constants.ACTION_LIST');
            $nowUserId = $this->getNowUser();

            $act['user_id'] = $nowUserId;
            $act['action_type'] = $actionList[$this->moduleKey]['deleteProduct'];
            $act['act_time'] = date('Y-m-d H:i:s');
            $act['is_admin'] = 1;
            $act['action_detail'] = '删除商品';
            $act['target_id'] = $id;

            $queueName = Config::get('constants.LOG_QUEUE_NAME');
            LogAction::dispatch($act)->onQueue($queueName);

            $rst['code'] = 100;
            return json_encode($rst);
        }
    }
}
