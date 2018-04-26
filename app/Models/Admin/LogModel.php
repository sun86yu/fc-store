<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class LogModel extends Model
{
    //
    protected $table = 't_log';

    public $timestamps = false;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['user_id', 'action_type', 'act_time', 'is_admin', 'action_detail', 'target_id'];

    public function role()
    {
        return $this->belongsTo('App\Models\Admin\AdminModel', 'user_id', 'id');
    }
}
