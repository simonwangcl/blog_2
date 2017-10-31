<?php

namespace App\Helper;


class RedisBaseHelper
{

    /**
     * 对需要保存的数据加密
     * @param  $value
     * @return type
     */
    protected static function valueEn($value)
    {
        return serialize($value);
    }

    /**
     * 对从缓存获取的数据解密
     * @param  $value
     * @return type
     */
    protected static function valueDe($value)
    {
        return unserialize($value);
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
        return $redis->setex($key, $time, self::valueEn($value));
    }

    /**
     * 获取缓存数据
     * @param  $key
     * @return
     */
    public static function fetch($key)
    {
        $redis = self::getRedis();
        $value = $redis->get($key);
        return self::valueDe($value);
    }

    /**
     * 获取缓存数据剩余时间
     * @param  $key
     * @return
     */
    public static function ttl($key)
    {
        $redis = self::getRedis();
        return $redis->ttl($key);
    }


    /**
     * 删除缓存数据
     * @param  $key
     * @return
     */
    public static function delete($key)
    {
        $redis = self::getRedis();
        if ($redis->get($key)) {
            return $redis->del(array($key));
        }
    }
}
