<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    //
    public function index()
    {
        $productActive = true;
        return view('Admin/Product/products', compact('productActive'));
    }
}
