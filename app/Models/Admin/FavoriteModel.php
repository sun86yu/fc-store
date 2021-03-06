<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class FavoriteModel extends Model
{
    //
    protected $table = 't_favorite';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\Admin\UserModel', 'user_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Admin\ProductModel', 'pro_id', 'id');
    }
}
