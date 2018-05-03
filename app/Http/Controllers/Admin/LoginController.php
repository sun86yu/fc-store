<?php

namespace App\Http\Controllers\Admin;

use Laravel\Passport\HasApiTokens;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    use HasApiTokens;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Admin/login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        //
    }
}
