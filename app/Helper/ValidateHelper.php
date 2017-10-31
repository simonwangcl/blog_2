<?php

namespace App\Helper;

class ValidateHelper
{
    public static function isEmail($str)
    {
        return filter_var($str, FILTER_VALIDATE_EMAIL);
    }

    public static function isPhone($str)
    {
        return preg_match("/^1[34578]\d{9}$/", $str);
    }

    public static function isUrl($str)
    {
        return filter_var($str, FILTER_VALIDATE_URL);
    }

    public static function isIp($str)
    {
        return filter_var($str, FILTER_VALIDATE_IP);
    }
}
