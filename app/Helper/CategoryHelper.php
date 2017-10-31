<?php

namespace App\Helper;

use App\Model\CategoryModel;
use App\Helper\RedisMenuHelper;

class CategoryHelper
{
    private static $category;

    private static function getCategoryKey()
    {
        return "CATEGORY";
    }

    public static function getCategories()
    {
        if (!self::$category) {
            $key = self::getCategoryKey();
            self::$category = RedisMenuHelper::fetch($key);
            if (!self::$category) {
                self::setCategory();
                self::$category = RedisMenuHelper::fetch($key);
            }
        }
        return self::$category;
    }

    public static function setCategory($cache = 86400)//3600*24
    {
        $key = self::getCategoryKey();
        $value = CategoryModel::where('pid', 0)->with('children')->orderBy('rank')->get();
        RedisMenuHelper::save($key, $value, $cache);
    }
}
