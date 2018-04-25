<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    //
    public function index()
    {
        $cateActive = true;
        return view('Admin/Category/cats', compact('cateActive'));
    }
    public function module()
    {
        $moduleActive = true;
        return view('Admin/Category/catmodule', compact('moduleActive'));
    }
    public function consts()
    {
        $constActive = true;
        return view('Admin/Category/consts', compact('constActive'));
    }
}
