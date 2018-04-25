<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //
    public function index()
    {
//        return redirect()->route('admin_user_add');
        $userActive = true;
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
