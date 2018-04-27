<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class CatConstModel extends Model
{
    //
    protected $table = 't_category_const';

    public $timestamps = false;

    public function module()
    {
        return $this->belongsTo('App\Models\Admin\CatModuleModel', 'id', 'mod_id');
    }
}
