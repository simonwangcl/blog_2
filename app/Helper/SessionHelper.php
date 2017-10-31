<?php

namespace App\Helper;

use App\Helper\CookieHelper;
use App\Helper\RedisSessionHelper;
use App\Helper\UserHelper;

/**
 * Description of SessionHelper
 *
 * @author Administrator
 */
class SessionHelper
{
    private static $userModel;

    public static function set($model, $time = 86400)//3600*24
    {
        $key = "blog_u_" . $model['id'] . "_" . str_random(32);
        RedisSessionHelper::save($key, $model['id'], $time);
        return $key;
    }


    public static function get()
    {
        if (!self::$userModel) {
            $token = \Cookie::get('BlogToken');
            $userId = RedisSessionHelper::fetch($token);
            self::$userModel = UserHelper::info($userId);
        }
        return self::$userModel;
    }

    public static function forget()
    {
        $token = \Cookie::get('BlogToken');
        RedisSessionHelper::delete($token);
        CookieHelper::forget("BlogToken");
    }

    public static function keep()
    {
        $token = \Cookie::get('BlogToken');
        $data = RedisSessionHelper::fetch($token);
        if ($data) {
            RedisSessionHelper::save($token, $data, 24 * 3600);
        }
    }
}
