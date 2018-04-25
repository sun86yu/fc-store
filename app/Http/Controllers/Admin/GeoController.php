<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GeoController extends Controller
{
    //
    public function index()
    {
        $geoActive = true;
        return view('Admin/Geo/geos', compact('geoActive'));
    }
}
