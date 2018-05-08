<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class OrdersModel extends Model
{
    //
    protected $table = 't_orders';
    public $timestamps = false;

    /**
     * 真实的收件地址
     */
    public function address()
    {
        return $this->belongsTo('App\Models\Admin\ReceiveAddressModel', 'real_address_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Admin\UserModel', 'user_id', 'id');
    }
}
