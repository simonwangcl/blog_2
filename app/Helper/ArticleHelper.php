<?php

namespace App\Helper;

use App\Model\ArticleModel;
use App\Helper\RedisDataHelper;
use App\Helper\IpHelper;
use App\Model\PointModel;


class ArticleHelper
{
    private static $sticky;//置顶
    private static $hotArticle;//热门文章

//    获取置顶文章的 key 值
    private static function getStickyKey()
    {
        return "STICKY";
    }

//    获取热门文章的 key 值
    private static function getHotArticleKey()
    {
        return "HOTARTICLE";
    }

//    获取置顶文章
    public static function getStickies()
    {
        if (!self::$sticky) {
            $key = self::getStickyKey();
            self::$sticky = RedisDataHelper::fetch($key);
            if (!self::$sticky) {
                self::setStickies();
                self::$sticky = RedisDataHelper::fetch($key);
            }
        }
        return self::$sticky;
    }

//    保存置顶文章到 Redis
    public static function setStickies($cache = 86400)//3600*24
    {
        $key = self::getStickyKey();
        $value = ArticleModel::where('state', 1)->where('sticky', 1)->select('id', 'title')->orderBy('updated_at', 'desc')->limit(5)->get();
        RedisDataHelper::save($key, $value, $cache);
    }

//    获取热门文章，浏览量最高
    public static function getHotArticles($cache = 3600)
    {
        if (!self::$hotArticle) {
            $key = self::getHotArticleKey();
            self::$hotArticle = RedisDataHelper::fetch($key);
            if (!self::$hotArticle) {
                $value = ArticleModel::where('state', 1)->select('id', 'title')->orderBy('count', 'desc')->limit(5)->get();
                RedisDataHelper::save($key, $value, $cache);
                self::$hotArticle = RedisDataHelper::fetch($key);
            }
        }
        return self::$hotArticle;
    }

//    判断同一个 IP 当天有没有浏览过该文章
    public static function checkForIp($id)
    {
        $key = 'ARTICLE_' . $id;
        $result = RedisDataHelper::fetch($key);
        $ip = IpHelper::getUserIp();
        if ($result) {
            if (in_array($ip, $result)) {
                return true;
            }
            array_push($result, $ip);
        } else {
            $result = array($ip);
        }
        $cache = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1 - time();
        RedisDataHelper::save($key, $result, $cache);
        return false;
    }

//    判断用户是否点赞过这篇文章
    public static function checkPoint($userId, $articleId, $type)
    {
        $point = PointModel::where('user_id', $userId)->where('article_id', $articleId)->where('type', $type)->first();
        if($point){
            return $point->state;
        }
        return false;
    }
}
