<?php

namespace App\Helper;

class CookieHelper extends \Cookie
{

    /**
     * 设定cookies信息
     * @param type $key
     * @param type $value
     * @param type $timeout
     */
    public static function set($key, $value, $timeout = NULL)
    {
        return \Cookie::make($key, $value, $timeout, '/');
    }

    public static function forget($key)
    {
        return \Cookie::forget($key, '/');
    }
}
