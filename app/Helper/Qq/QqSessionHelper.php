<?php

namespace App\Helper\Qq;

use App\Helper\CookieHelper;
use App\Helper\RedisSessionHelper;
use App\Helper\Qq\QqUserHelper;

/**
 * Description of SessionHelper
 *
 * @author Administrator
 */
class QqSessionHelper
{
    private static $qqUserModel;

    public static function set($model, $time = 86400)//3600*24
    {
        $key = "blog_qq_" . $model['id'] . "_" . str_random(32);
        RedisSessionHelper::save($key, $model['id'], $time);
        return $key;
    }


    public static function get()
    {
        if (!self::$qqUserModel) {
            $token = \Cookie::get('QqBlogToken');
            $userId = RedisSessionHelper::fetch($token);
            self::$qqUserModel = QqUserHelper::info($userId);
        }
        return self::$qqUserModel;
    }

    public static function forget()
    {
        $token = \Cookie::get('QqBlogToken');
        RedisSessionHelper::delete($token);
        CookieHelper::forget("QqBlogToken");
    }

    public static function keep()
    {
        $token = \Cookie::get('QqBlogToken');
        $data = RedisSessionHelper::fetch($token);
        if ($data) {
            RedisSessionHelper::save($token, $data, 24 * 3600);
        }
    }
}
