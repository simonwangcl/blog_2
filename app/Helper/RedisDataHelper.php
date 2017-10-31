<?php

namespace App\Helper;

class RedisDataHelper extends RedisBaseHelper
{
    public static function getRedis()
    {
        return \Redis::connection("data");
    }

    /**
     * 保存数据到redis
     * @param $key
     * @param $value
     * @param $time
     * @return type
     */
    public static function save($key, $value, $time = 3600)
    {
        $redis = self::getRedis();
        return $redis->setex($key, $time, parent::valueEn($value));
    }

    /**
     * 获取缓存数据
     * @param type $key
     * @return type
     */
    public static function fetch($key)
    {
        $redis = self::getRedis();
        $value = $redis->get($key);
        return parent::valueDe($value);
    }

    /**
     * 获取缓存生存时间
     * @param type $key
     * @return type
     */
    public static function ttl($key)
    {
        $redis = self::getRedis();
        return $redis->ttl($key);
    }

    /**
     * 往list里面插入一个数据
     */
    public static function push($key, $value)
    {
        $redis = self::getRedis();
        $redis->lPush($key, parent::valueEn($value));
    }

    /**
     * 从list剔出一个数据
     * @param  $key
     * @return type
     */
    public static function pop($key)
    {
        $redis = self::getRedis();
        $value = $redis->lPush($key);
        return parent::valueDe($value);
    }


    /**
     * 删除缓存数据
     * @param  $key
     * @return type
     */
    public static function delete($key)
    {
        $redis = self::getRedis();
        if ($redis->get($key)) {
            return $redis->del(array($key));
        }
    }
}
