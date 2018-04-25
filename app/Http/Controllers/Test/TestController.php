<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Contracts\StringIF;
use App\Jobs\ImagesPress;
use App\Services\FacadeStringTools;

class TestController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index(StringIF $tools)
    {
        echo FacadeStringTools::strlen('are you ok?');
        echo FacadeStringTools::extendLen('i\'m ok');
        $userid = 3;
        ImagesPress::dispatch($userid)->onQueue('default');
//        ImagesPress::dispatch($userid)
//            ->delay(now()->addSecond(10));


        return $tools->strlen('My Test String') . '<br />' . $tools->echo('My Test String');
    }

    public function test($id)
    {
        return 'Controller Param Id: ' . $id;
    }

    public function __invoke()
    {
        return 'do somethin!';
    }

    public function show()
    {
        return 'show';
    }

    public function create()
    {
        return 'create';
    }
}
