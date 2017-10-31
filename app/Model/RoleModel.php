<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\RoleMenuRelationModel;

class RoleModel extends Model
{
    protected $table = 'roles';

    public static $roleAdmin = 1;//超级管理员
    public static $roleAuthor = 2;//作者
    public static $roleVisitor = 3;//游客


    /**
     * 获取角色下面的所有菜单详情。
     */
    public function menus()
    {
        return $this->belongsToMany('App\Model\MenuModel', with(new RoleMenuRelationModel())->getTable(), 'role_id', 'menu_id')->orderBy('rank');
    }

    /**
     * 获取角色下面的所有菜单id。
     */
    public function menu()
    {
        return $this->hasMany('App\Model\RoleMenuRelationModel', 'role_id', 'id');
    }

    public static function boot()
    {
        parent::boot();

        self::deleting(function ($model) {
            RoleMenuRelationModel::where('role_id', $model->id)->delete();
            return TRUE;
        });
    }
}
