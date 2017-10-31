<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Web\BaseController;

use App\Model\UserModel;
use App\Model\QqUserModel;
use App\Helper\SessionHelper;
use App\Helper\CookieHelper;
use App\Model\RoleModel;
use App\Helper\MenuHelper;
use App\Helper\FileHelper;
use App\Helper\Qq\QqLoginHelper;
use App\Helper\Qq\QqSessionHelper;

class QqController extends BaseController
{
    private static $url = 'back';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        setcookie(self::$url,$_SERVER['HTTP_REFERER'],time()+300);
        $qq = new QqLoginHelper();
        $qq->qqLogin();
    }

    public function login(Request $request)
    {
        $qq = new QqLoginHelper();
        $access_token = $qq->getAccessToken();
        $openid = $qq->getOpenid();
        $model = QqUserModel::where('open_id',$openid)->first();
        if(!$model){
            $user_data = $qq->get_user_info();
            $url = $user_data['figureurl_qq_2'] ? : $user_data['figureurl_qq_1'];
            $path = FileHelper::downloadAvatar($url, 'qq');
            if(empty($path)){
                $path = '/img/default/avatar.jpg';
            }
            $model = new QqUserModel();
            $model->open_id = $openid;
            $model->name = $user_data['nickname'];
            $model->gender = $user_data['gender'];
            $model->province = $user_data['province'];
            $model->city = $user_data['city'];
            $model->year = $user_data['year'];
            $model->image = $path;
            $model->save();
        }
        $url = $_COOKIE[self::$url] ? : config('domain.http_domain');//没有值跳回首页
        CookieHelper::forget(self::$url);
        if($model->user_id && $model->user->role_id != RoleModel::$roleVisitor){
            MenuHelper::setMenus($model->user, 86400);
            $token = SessionHelper::set($model->user, 86400);
            return redirect($url)->withCookie(CookieHelper::set('BlogToken', $token));
        }else{
            $token = QqSessionHelper::set($model, 86400);
            return redirect($url)->withCookie(CookieHelper::set('QqBlogToken', $token, 2592000));
        }
    }

    public function loginOut(){
        QqSessionHelper::forget();
        SessionHelper::forget();
        $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
        return redirect($url);
    }
}
