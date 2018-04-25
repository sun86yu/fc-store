<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogController extends Controller
{
    //
    public function index()
    {
        $logActive = true;
        return view('Admin/Log/logs', compact('logActive'));
    }
}
