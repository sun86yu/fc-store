<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CategoryModel extends Model
{
    //
    protected $table = 't_category';

    public $timestamps = false;

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['cat_name', 'cat_level', 'cat_parent', 'is_active'];

    public function moduleList()
    {
        return $this->hasMany('App\Models\Admin\CatModuleModel', 'cat_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne('App\Models\Admin\CategoryModel', 'id', 'cat_parent');
    }

    public static function getParent($id)
    {
        $catKey = 'Cat_' . $id;
        $cat = Cache::get($catKey);
        if ($cat == null) {
            $cat = self::with('parent')->find($id);
            Cache::forever($catKey, $cat);
        }
        $parent = $cat->parent;

        return $parent;
    }
}
