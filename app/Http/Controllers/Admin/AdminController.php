<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\AdminTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    use AdminTools;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $moduleLists = Config::get('constants.MODULE_LIST');

        $lists = DB::table('t_admin as a')
            ->join('t_role as r', 'a.role_id', '=', 'r.id')
            ->select('a.*', 'r.role_name')
            ->paginate(2);

        $adminActive = true;
        return view('Admin/User/admins', compact('adminActive', 'lists', 'moduleLists'));
    }

    public function role()
    {
        //
        $lists = DB::table('t_role')->paginate(2);
        $moduleLists = Config::get('constants.MODULE_LIST');

        $roleActive = true;
        return view('Admin/User/admin_role', compact('roleActive', 'lists', 'moduleLists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function showadmin(Request $request, $id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function showrole(Request $request, $id)
    {
        //
        $rst = [];
        if ($request->ajax() && $request->isMethod('GET')) {

            $role = DB::table('t_role')->find($id);

            $moduleLists = Config::get('constants.MODULE_LIST');

            $rightBin = decbin($role->role_right);
            $rightBin = str_pad($rightBin, count($moduleLists), '0', STR_PAD_LEFT);

            $idx = 0;
            $right = [];
            foreach ($moduleLists as $key => $name) {
                if ($rightBin[$idx] == 1) {
                    $right[$key] = 1;
                } else {
                    $right[$key] = 0;
                }
                $idx++;
            }

            $rst['code'] = 100;
            $rst['data']['name'] = $role->role_name;
            $rst['data']['right'] = $right;
        } else if ($request->isMethod('POST')) {
            $moduleLists = Config::get('constants.MODULE_LIST');

            $data = $request->post();

            $rightStr = str_pad('', count($moduleLists), '0', STR_PAD_LEFT);
            $idx = 0;
            foreach ($moduleLists as $key => $module) {
                if (array_key_exists('role_right_' . $key, $data) && $data['role_right_' . $key] == 'on') {
                    $rightStr[$idx] = 1;
                }
                $idx++;
            }

            if ($data['role_id'] > 0) {
                DB::table('t_role')
                    ->where('id', $data['role_id'])
                    ->update(['role_name' => $data['role_name'], 'role_right' => bindec($rightStr)]);
            } else {
                DB::table('t_role')->insert([
                    'role_name' => $data['role_name'],
                    'role_right' => bindec($rightStr),
                ]);
            }

            return redirect()->route('admin_role_list');
        } else if ($request->isMethod('DELETE')) {
            DB::table('t_role')->where('id', $id)->delete();

            $rst['code'] = 100;
        }

        return json_encode($rst);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
