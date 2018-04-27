<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class GeoModel extends Model
{
    //
    protected $table = 't_geo';

    public $timestamps = false;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['geo_name', 'geo_level', 'is_active'];
}
