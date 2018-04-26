<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AdminModel extends Model
{
    //
    protected $table = 't_admin';
    // 默认会假定我们表中有一个叫 id 的主键.如果不是,可以自己定义
    protected $primaryKey = 'id';
    // 会默认数据表中存在 created_at 和 updated_at 这两个字段
    public $timestamps = false;
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['admin_name', 'admin_pwd', 'role_id', 'admin_email', 'status', 'nick_name', 'create_time'];

    public function role()
    {
//        return $this->hasOne('App\Models\Admin\RoleModel', 'id', 'role_id');
        return $this->belongsTo('App\Models\Admin\RoleModel', 'role_id', 'id');
    }

    public function adminLogs()
    {
        return $this->hasMany('App\Models\Admin\LogModel', 'user_id', 'id');
    }
}
