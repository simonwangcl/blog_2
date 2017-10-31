<?php

namespace App\Helper;

use App\Model\TagModel;
use App\Helper\RedisDataHelper;

class TagHelper
{
    private static $tag;

    private static function getTagKey()
    {
        return "TAG";
    }

    public static function getTags()
    {
        if (!self::$tag) {
            $key = self::getTagKey();
            self::$tag = RedisDataHelper::fetch($key);
            if (!self::$tag) {
                self::setTags();
                self::$tag = RedisDataHelper::fetch($key);
            }
        }
        return self::$tag;
    }

    public static function setTags($cache = 86400)//3600*24
    {
        $key = self::getTagKey();
        $value = TagModel::with(['articles' => function($query){
            return $query->select('state');
        }])->get();
        foreach ($value as &$tag){
            if($tag->articles){
                $tag->articles = $tag->articles->count();
            }
        }
        RedisDataHelper::save($key, $value, $cache);
    }
}
