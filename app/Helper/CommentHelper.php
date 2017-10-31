<?php

namespace App\Helper;

use App\Model\CommentModel;
use App\Helper\RedisDataHelper;

class CommentHelper
{
    private static $comment;

    private static function getCommentKey()
    {
        return "COMMENT";
    }

    public static function getComments()
    {
        if (!self::$comment) {
            $key = self::getCommentKey();
            self::$comment = RedisDataHelper::fetch($key);
            if (!self::$comment) {
                self::setComments();
                self::$comment = RedisDataHelper::fetch($key);
            }
        }
        return self::$comment;
    }

    public static function setComments($cache = 86400)//3600*24
    {
        $key = self::getCommentKey();
        $value = CommentModel::with('user')->orderBy('id', 'desc')->limit(5)->get();
        RedisDataHelper::save($key, $value, $cache);
    }
}
