<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\OrdersModel;

class OrderController extends Controller
{
    //
    public function index()
    {
        $orderActive = true;
        $lists = OrdersModel::with('address')->with('user')->get();
        print_r($lists);
        return view('Admin/Order/orders', compact('orderActive'));
    }
}
