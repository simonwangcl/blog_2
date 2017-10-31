<?php

namespace App\Helper;

use Illuminate\Http\Request;
use App\Helper\CurlHelper;

class FileHelper
{
    public static $path = "base_path('public/storage/')";
    public static $avatarQq = 'qq';//qq头像
    public static $avatarWx = 'wx';//微信头像


    /**
     * @param $url
     * @param $avatar
     * @return string
     */
    public static function downloadAvatar($url, $avatar)
    {
        if (!$url) {
            return "";
        }
        $parseUrl = parse_url($url);
        if (!isset($parseUrl['host'])) {
            return "";
        }
        $avatarFileName = "";
        $imageContent = NULL;
        $time = 1;
        while (!$imageContent && $time < 3) {
            $imageContent = CurlHelper::request($url);
            $time++;
        }
        if ($imageContent) {
            $avatarFileName = self::saveAvatarByStr($imageContent, "jpg", $avatar);
        }
        return $avatarFileName;
    }

    public static function saveAvatarByStr($str, $ext, $avatar)
    {
        $fileName = self::bringFileName($ext);
        $path = '/storage/avatars/';
        $path .= $avatar ? $avatar . '/'  .$fileName : $fileName;
        if (!file_put_contents(base_path('public') . $path, $str)) {
            $path = '';
        }
        return $path;
    }

    public static function bringFileName($ext)
    {
        return date('YmdHis') . str_random(6) . '.' . $ext;
    }
}