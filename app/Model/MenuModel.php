<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MenuModel extends Model
{
    protected $table = 'menus';

    /**
     * 获取菜单组下面的所有子菜单。
     */
    public function children()
    {
        return $this->hasMany('App\Model\MenuModel', 'pid', 'id')->orderBy('rank');
    }
}
