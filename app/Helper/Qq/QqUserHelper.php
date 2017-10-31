<?php

namespace App\Helper\Qq;

use App\Model\QqUserModel;
use App\Helper\Qq\QqSessionHelper;
use Illuminate\Support\Facades\Session;
use App\Helper\RedisDataHelper;

/**
 * Description of UserService
 *
 * @author Administrator
 */
class QqUserHelper
{

    public static function info($userId)
    {
        $model = self::getInfo($userId);
        if ($model) {
            return $model;
        }
        $model = QqUserModel::find($userId);
        if (!$model) {
            return FALSE;
        }
        self::setInfo($userId, $model);
        return $model;
    }

    private static function getInfoKey($userId)
    {
        return "QQUSERINFO_" . $userId;
    }

    public static function getInfo($userId)
    {
        $key = self::getInfoKey($userId);
        return RedisDataHelper::fetch($key);
    }

    public static function setInfo($userId, $data)
    {
        $key = self::getInfoKey($userId);
        RedisDataHelper::save($key, $data);
    }

    public static function delInfo($userId)
    {
        $key = self::getInfoKey($userId);
        RedisDataHelper::delete($key);
    }

    public static function keepInfo($data = null){
        if(!$data){
            $data = QqSessionHelper::get();
        }
        $userId = $data->id;
        $key = self::getInfoKey($userId);
        RedisDataHelper::save($key, $data, 24 * 3600);
    }
}
