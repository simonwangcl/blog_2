<?php

namespace App\Helper;

use App\Model\RoleMenuRelationModel;
use App\Model\MenuModel;
use App\Helper\RedisMenuHelper;

class MenuHelper
{
    private static $userMenus;

    private static function getMenusKey($userId)
    {
        return "MENUS_" . $userId;
    }

    public static function getMenus($userId)
    {
        if (!self::$userMenus) {
            $key = self::getMenusKey($userId);
            self::$userMenus = RedisMenuHelper::fetch($key);
        }
        return self::$userMenus;
    }

    public static function setMenus($model, $time = 86400)
    {
        $data = self::getMenusByRoleId($model->role_id);
        $key = self::getMenusKey($model->id);
        RedisMenuHelper::save($key, $data, $time);
    }

    public static function delMenus($userId)
    {
        $key = self::getMenusKey($userId);
        RedisMenuHelper::delete($key);
    }

    public static function getMenusByRoleId($id)
    {
        $menus = MenuModel::where('pid', 0)->with('children')->orderBy('rank')->get();
        $menuIds = RoleMenuRelationModel::where('role_id', $id)->pluck('menu_id')->toArray();
        if ($menus->toArray()) {
            foreach ($menus as $key => $menu) {
                if (!in_array($menu->id, $menuIds)) {
                    unset($menus[$key]);
                } else {
                    if (isset($menus->children) && $menus->children->toArray()) {
                        foreach ($menus->children as $k => $child) {
                            if (!in_array($child->id, $menuIds)) {
                                unset($menus->children[$k]);
                            }
                        }
                    }
                }
            }
        }
        return $menus->toArray();
    }
}
