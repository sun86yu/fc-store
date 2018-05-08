<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ReceiveAddressModel extends Model
{
    //
    protected $table = 't_order_address';
    public $timestamps = false;

    public function order()
    {
        return $this->belongsTo('App\Models\Admin\OrdersModel', 'real_address_id', 'id');
    }
}
