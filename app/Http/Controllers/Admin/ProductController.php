<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\CatModuleModel;
use App\Models\Admin\ProductModel;
use Illuminate\Http\Request;
use App\Http\Traits\AdminTools;
use Illuminate\Support\Facades\Config;
use App\Models\Admin\CategoryModel;
use Illuminate\Support\Facades\Validator;
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

    public function create(Request $request, $id = 0)
    {
        $this->pageTitle = '商品管理';
        $this->pageSubTitle = '新建商品';
        $this->pageModuleName = '商品管理';
        $this->pageModuleUrl = route('admin_product_list');
        $this->pageFuncName = '添加商品';

        $productAddActive = true;

        $topCat = CategoryModel::where('cat_level', 1)->get();

        $product = new ProductModel();

        if ($request->has('product_id')) {
            $id = $request->input('product_id');
        }

        if ($id > 0) {
            $product = ProductModel::with(['category' => function ($query) {
                $query->where('is_active', 1);
            }])->with('category.parent')->with(['category.moduleList' => function ($query) {
                $query->where('is_active', 1);
            }])->find($id);
            $firstSecondCat = CategoryModel::getBrotherCat($product->cat_id);
        } else {
            $firstSecondCat = CategoryModel::where(['cat_level' => 2, 'cat_parent' => $topCat[0]->id])->get();
        }

        if ($request->isMethod('POST')) {

            if ($request->input('product_id') == 0) {
                $product->create_time = date("Y-m-d H:i:s");
            }

            $product->pro_name = $request->input('pro_name');
            $product->cat_id = $request->input('sec_cat');
            $product->info = '';
            $product->price = $request->input('price');
            $product->remain_cnt = $request->input('remain_cnt');
            $product->saled_cnt = $request->input('saled_cnt');
            $product->status = $request->input('product_status');
            $product->content = $request->input('product_content');

            $imgStr = $request->input('pro_img');

            $imgArr = explode(',', $imgStr);

            $pro_img = '';
            if (is_array($imgArr) && count($imgArr) > 0) {
                foreach ($imgArr as $item) {
                    if (strlen($item) > 3) {
                        $pro_img .= $item . ',';
                    }
                }
            }

            if (strlen($pro_img) > 0) {
                $product->pro_img = substr($pro_img, 0, strlen($pro_img) - 1);
            }

            // 取扩展信息并存储
            $modList = CatModuleModel::where(['is_active' => 1, 'cat_id' => $product->cat_id])->get();

            $ext = [];
            foreach ($modList as $loopModule) {
                $loopName = $loopModule->mod_en_name;

                $ext[$loopModule->id] = $request->input($loopName);
            }

            $product->info = json_encode($ext);

            // 添加或编辑产品信息
            // 名称长度检测
            $input = [
                'pro_name' => $product->pro_name,
                'price' => $product->price,
                'remain_cnt' => $product->remain_cnt,
                'saled_cnt' => $product->saled_cnt,
            ];
            $rules = [
                'pro_name' => 'required|max:100',
                'remain_cnt' => 'required|numeric',
                'saled_cnt' => 'required|numeric',
                'price' => 'required|numeric',
            ];
            $messages = [
                'required' => ':attribute 值必须填写.',
                'max' => ':attribute 长度不能超过 :max.',
                'numeric' => ':attribute 必须是数字.',
            ];

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                $error = $validator->errors()->first();

                return view('Admin/Product/productadd', array_merge(compact('productAddActive', 'error', 'product', 'topCat', 'firstSecondCat'), $this->getCommonParm()));
            }

            $product->save();

            return redirect()->route('admin_product_list');
        }

        return view('Admin/Product/productadd', array_merge(compact('productAddActive', 'topCat', 'firstSecondCat', 'product'), $this->getCommonParm()));
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
