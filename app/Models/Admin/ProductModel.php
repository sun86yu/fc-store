<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    //
    protected $table = 't_product';

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo('App\Models\Admin\CategoryModel', 'cat_id', 'id');
    }
}
