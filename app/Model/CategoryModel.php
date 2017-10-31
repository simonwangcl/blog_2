<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';

    /**
     * 获取二级分类。
     */
    public function children()
    {
        return $this->hasMany('App\Model\CategoryModel', 'pid', 'id')->orderBy('rank');
    }
}