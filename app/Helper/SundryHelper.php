<?php

namespace App\Helper;

use App\Model\FriendLinkModel;
use App\Model\ResumeModel;
use App\Helper\RedisDataHelper;

class SundryHelper
{
    private static $friendLink;
    private static $resume;

    private static function getFriendLinkKey()
    {
        return "FRIENDLINK";
    }

    public static function getFriendLink()
    {
        if (!self::$friendLink) {
            $key = self::getFriendLinkKey();
            self::$friendLink = RedisDataHelper::fetch($key);
            if (!self::$friendLink) {
                self::setFriendLink();
                self::$friendLink = RedisDataHelper::fetch($key);
            }
        }
        return self::$friendLink;
    }

    public static function setFriendLink($cache = 86400)//3600*24
    {
        $key = self::getFriendLinkKey();
        $value = FriendLinkModel::orderBy('rank', 'desc')->select('name', 'href')->get();
        RedisDataHelper::save($key, $value, $cache);
    }

    private static function getResueKey()
    {
        return "RESUME";
    }

    public static function getResume()
    {
        if (!self::$resume) {
            $key = self::getResueKey();
            self::$resume = RedisDataHelper::fetch($key);
            if (!self::$resume) {
                self::setResume();
                self::$resume = RedisDataHelper::fetch($key);
            }
        }
        return self::$resume;
    }

    public static function setResume($cache = 86400)//3600*24
    {
        $key = self::getResueKey();
        $value = ResumeModel::orderBy('rank', 'desc')->select('name', 'content')->get();
        RedisDataHelper::save($key, $value, $cache);
    }
}
