<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class CatModuleModel extends Model
{
    //
    protected $table = 't_category_module';

    public $timestamps = false;

    public function constant()
    {
        return $this->hasMany('App\Models\Admin\CatConstModel', 'mod_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Admin\CategoryModel', 'cat_id', 'id');
    }
}
