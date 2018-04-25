<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    //
    public function index()
    {
        $orderActive = true;
        return view('Admin/Order/orders', compact('orderActive'));
    }
}
