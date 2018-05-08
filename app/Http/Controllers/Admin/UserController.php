<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\UserModel;

class UserController extends Controller
{
    //
    public function index()
    {
//        return redirect()->route('admin_user_add');
        $userActive = true;
        $list = UserModel::with('address')->with('orders')->get();
        return view('Admin/User/users', compact('userActive'));
    }

    public function identy()
    {
        $identyActive = true;
        return view('Admin/User/user_identy', compact('identyActive'));
    }

    public function edit($id)
    {
        return 'Edit User!' . $id;
    }
}
