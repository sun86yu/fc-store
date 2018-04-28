<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\AdminTools;
use App\Jobs\LogAction;
use App\Models\Admin\NewsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    use AdminTools;

    public function __construct()
    {
        $this->moduleKey = 'article';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $newsActive = true;

        $this->pageTitle = '文章管理';
        $this->pageSubTitle = '系统内所有文章管理';
        $this->pageModuleName = '文章管理';
        $this->pageModuleUrl = route('admin_article_list');
        $this->pageFuncName = '文章查询';

        $where = [];

        if ($request->input('search_status', -1) != -1) {
            $where['status'] = $request->input(['search_status']);
        }
        if ($request->input('search_is_gallarry') == 'on') {
            $where['is_galarry'] = 1;
        }
        if ($request->input('search_is_link') == 'on') {
            $where['is_link'] = 1;
        }

//        DB::connection()->enableQueryLog();

        $lists = NewsModel::where($where)->orderBy('create_time', 'desc')->paginate($this->pageSize);

        $lists = $lists->appends([
            'search_status' => $request->input('search_status', -1),
            'search_is_gallarry' => $request->input('search_is_gallarry'),
            'search_is_link' => $request->input('search_is_link')
        ]);

        return view('Admin/Article/articles', array_merge(compact('newsActive', 'lists'), $this->getCommonParm()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $this->pageTitle = '文章管理';
        $this->pageSubTitle = '新建文章';
        $this->pageModuleName = '文章管理';
        $this->pageModuleUrl = route('admin_article_list');
        $this->pageFuncName = '添加文章';

        if ($request->isMethod('POST')) {
            // 名称长度检测
            $input = [
                'article_title' => $request->input('article_title'),
            ];
            $rules = [
                'article_title' => 'required|max:200',
            ];
            $messages = [
                'required' => ':attribute 值必须填写.',
                'max' => ':attribute 长度不能超过 :max.',
            ];

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                $newAddActive = true;
                $error = $validator->errors()->first();

                return view('Admin/Article/articleadd', array_merge(compact('newAddActive', 'error'), $this->getCommonParm()));
            }

            $is_link = 0;
            $is_galarry = 0;
            if (strtolower(trim($request->input('is_link'))) == 'on') {
                $is_link = 1;
            }
            if (strtolower(trim($request->input('is_galarry'))) == 'on') {
                $is_galarry = 1;
            }

            $actionList = Config::get('constants.ACTION_LIST');
            $article = new NewsModel();

            $act['action_detail'] = '新建文章';
            $act['action_type'] = $actionList[$this->moduleKey]['createArticle'];

            if ($request->input('article_id') > 0) {
                $article = NewsModel::find($request->input('article_id'));
                $act['action_detail'] = '更新文章';
                $act['action_type'] = $actionList[$this->moduleKey]['editArticle'];
            }

            $article->title = trim($request->input('article_title'));
            $article->content = $request->input('article_content');
            $article->status = $request->input('article_status');
            $article->link_url = $request->input('link_url');
            $article->head_img = $request->input('article_img');
            $article->create_time = date('Y-m-d H:i:s');

            $article->is_link = $is_link;
            $article->is_galarry = $is_galarry;

            $article->save();

            $nowUserId = $this->getNowUser();

            $act['user_id'] = $nowUserId;
            $act['act_time'] = date('Y-m-d H:i:s');
            $act['is_admin'] = 1;

            $act['target_id'] = $article->id;

            $queueName = Config::get('constants.LOG_QUEUE_NAME');
            LogAction::dispatch($act)->onQueue($queueName);

            return redirect()->route('admin_article_list');
        }

        $newAddActive = true;

        return view('Admin/Article/articleadd', array_merge(compact('newAddActive'), $this->getCommonParm()));
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
        $file = $request->file('files');
        if (is_array($file)) {
            $file = $file[0];
        }
        $dirName = date('Y-m-d');

//        Storage::put($dirName, $file);
        $path = $file->store($dirName);

        return json_encode([
            'code' => 100,
            'files' => [['name' => '/storage/' . $path]]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $this->pageTitle = '文章管理';
        $this->pageSubTitle = '编辑文章';
        $this->pageModuleName = '文章管理';
        $this->pageModuleUrl = route('admin_article_list');
        $this->pageFuncName = '编辑文章';

        $article = NewsModel::find($id);

        $newAddActive = true;
        return view('Admin/Article/articleadd', array_merge(compact('newAddActive', 'article'), $this->getCommonParm()));
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
            NewsModel::where('id', $id)
                ->update(['status' => 0]);

            $actionList = Config::get('constants.ACTION_LIST');
            $nowUserId = $this->getNowUser();

            $act['user_id'] = $nowUserId;
            $act['action_type'] = $actionList[$this->moduleKey]['deleteArticle'];
            $act['act_time'] = date('Y-m-d H:i:s');
            $act['is_admin'] = 1;
            $act['action_detail'] = '删除文章';
            $act['target_id'] = $id;

            $queueName = Config::get('constants.LOG_QUEUE_NAME');
            LogAction::dispatch($act)->onQueue($queueName);

            $rst['code'] = 100;
            return json_encode($rst);
        }
    }
}
