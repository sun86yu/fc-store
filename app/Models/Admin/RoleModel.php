<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    //
    protected $table = 't_role';
    public $timestamps = false;

    /**
     * 获得拥有此权限的用户。
     */
    public function user()
    {
        return $this->hasMany('App\Models\Admin\AdminModel', 'role_id', 'id');
    }
}
