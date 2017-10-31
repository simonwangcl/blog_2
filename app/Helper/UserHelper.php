<?php

namespace App\Helper;

use App\Model\UserModel;
use App\Helper\SessionHelper;
use Illuminate\Support\Facades\Session;

/**
 * Description of UserService
 *
 * @author Administrator
 */
class UserHelper
{

    /**
     * 根据用户id获取用户模型
     * @param type $userId
     * @return boolean
     */
    public static function info($userId)
    {
        $model = self::getInfo($userId);
        if ($model) {
            return $model;
        }
        $model = UserModel::with('role')->find($userId);
        if (!$model) {
            return FALSE;
        }
        self::setInfo($userId, $model);
        return $model;
    }

    /**
     * 设置用户名密码
     * @param string $password
     * @param string $salt
     *
     * @return string
     */
    public static function setPassword($password, $salt)
    {
        return md5($salt . $password);
    }


    /**
     * 根据用户id获取保存用户信息的redis key
     * @param type $userId
     *
     * @return string
     */
    private static function getInfoKey($userId)
    {
        return "USERINFO_" . $userId;
    }

    /**
     * 根据用户id获取用户信息
     * @param type $userId
     *
     * @return string
     */
    public static function getInfo($userId)
    {
        $key = self::getInfoKey($userId);
        return RedisDataHelper::fetch($key);
    }

    /**
     * 设定用户信息
     * @param type $userModel
     *
     * @return string
     */
    public static function setInfo($userId, $data)
    {
        $key = self::getInfoKey($userId);
        RedisDataHelper::save($key, $data);
    }

    /**
     * 删除用户信息
     */
    public static function delInfo($userId)
    {
        $key = self::getInfoKey($userId);
        RedisDataHelper::delete($key);
    }

    public static function keepInfo($data = null){
        if(!$data){
            $data = SessionHelper::get();
        }
        $userId = $data->id;
        $key = self::getInfoKey($userId);
        RedisDataHelper::save($key, $data, 24 * 3600);
    }
}
