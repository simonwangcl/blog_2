<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

use App\Model\UserModel;
use App\Model\RoleModel;
use App\Helper\ValidateHelper;
use App\Helper\SessionHelper;
use App\Helper\CookieHelper;
use App\Helper\MenuHelper;
use App\Helper\UserHelper;

class LoginController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = SessionHelper::get();
        if($model){
            if($model->role_id != RoleModel::$roleVisitor){
                return redirect("/admin");
            }
        }
        $code = rand(1, 99999);
        return view('admin/login/index', compact('code'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = trim($request->input('name'));
        $password = trim($request->input('password'));
        $remember = $request->input('remember');

        if (ValidateHelper::isPhone($name)) {
            $userModel = UserModel::where('phone', $name)->first();
        } else if (ValidateHelper::isEmail($name)) {
            $userModel = UserModel::where('email', $name)->first();
        } else {
            $userModel = UserModel::where('name', $name)->first();
        }
        if ($userModel) {
            if ($userModel->role_id == 0 || $userModel->role_id == RoleModel::$roleVisitor) {
                return response()->json(['state' => 'error', 'message' => '没有权限登录！']);
            }
            if (md5($userModel->salt . $password) == $userModel->password) {
                $userModel['role'] = $userModel->role;
                if ($remember == 'true') {
                    MenuHelper::setMenus($userModel, 2592000);
                    $token = SessionHelper::set($userModel, 2592000);//redis里面保存一个月
                    return response()->json(['state' => 'success'])->withCookie(CookieHelper::set('BlogToken', $token, 2592000));
                } else {
                    MenuHelper::setMenus($userModel, 86400);
                    $token = SessionHelper::set($userModel, 86400);//redis里面保存一天
                    return response()->json(['state' => 'success'])->withCookie(CookieHelper::set('BlogToken', $token));
                }
            }
        }
        return response()->json(['state' => 'error', 'message' => '帐号密码错误！']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MenuHelper::delMenus($id);
        UserHelper::delInfo($id);
        SessionHelper::forget();
        return response()->json(['state' => 'success']);
    }
}
