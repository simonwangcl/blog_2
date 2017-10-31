<?php

namespace App\Helper;

class RedisSessionHelper extends RedisBaseHelper
{

    public static function getRedis()
    {
        return \Redis::connection("session");
    }

    /**
     * 保存数据到redis
     * @param  $key
     * @param  $value
     * @param  $time
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

    public static function ttl($key)
    {
        $redis = self::getRedis();
        return $redis->ttl($key);
    }


    /**
     * 删除缓存数据
     * @param type $key
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
