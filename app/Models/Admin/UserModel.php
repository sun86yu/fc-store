<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    //
    protected $table = 't_user';
    public $timestamps = false;

    /**
     * 用户的订单
     */
    public function orders()
    {
        return $this->hasMany('App\Models\Admin\OrdersModel', 'user_id', 'id');
    }
    /**
     * 用户的地址薄
     */
    public function address()
    {
        return $this->hasMany('App\Models\Admin\AddressModel', 'user_id', 'id');
    }
}
