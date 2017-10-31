<?php

namespace App\Http\Controllers\Admin;

use App\Helper\UserHelper;
use App\Providers\ViewServiceProvider;
use App\User;
use Hamcrest\SampleSelfDescriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\SessionHelper;
use App\Model\UserModel;
use App\Helper\ValidateHelper;
use App\Helper\ImageHelper;
use App\Model\QqUserModel;

class InfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->input('type', 'info');
        $user = SessionHelper::get();
        $user->qqModel = $user->qq;
        return view('admin.info.index', compact('type', 'user'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $keyword = $request->input('keyword');
        $result = QqUserModel::where('name', 'like', '%' . $keyword . '%')->get();
        return response()->json(['state' => 'success', 'result' => $result->toArray()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $type = $request->input('type');
        $model = SessionHelper::get();

        switch ($type) {
            case 'avatar':
                $image = $request->input('avatar');
                if ($model->image != $image) {
                    ImageHelper::deleteImage($model->image, 'avatar');
                    $model->image = $image;
                    $model->save();
                }
                UserHelper::keepInfo($model);
                return response()->json(['state' => 'success', 'message' => '头像修改成功！', 'type' => $type]);
                break;
            case 'info':
                $username = $request->input('username');
                $email = $request->input('email');
                $phone = $request->input('phone');

                if ($username != $model['name']) {
                    $userModel = UserModel::where('name', $username)->first();
                    if ($userModel) {
                        return response()->json(['state' => 'error', 'message' => '用户名已存在！']);
                    }
                }
                if ($email != $model['email']) {
                    $userModel = UserModel::where('email', $username)->first();
                    if ($userModel) {
                        return response()->json(['state' => 'error', 'message' => '邮箱地址已存在！']);
                    }
                }
                if ($phone != $model['phone']) {
                    $userModel = UserModel::where('phone', $username)->first();
                    if ($userModel) {
                        return response()->json(['state' => 'error', 'message' => '手机号码已存在！']);
                    }
                }
                $model->name = $username;
                $model->email = $email;
                $model->phone = $phone;
                $model->save();
                UserHelper::keepInfo($model);
                return response()->json(['state' => 'success', 'message' => '个人资料修改成功！', 'type' => $type]);

                break;
            case 'password':
                $old = trim($request->input('old'));
                $new = trim($request->input('new'));
                $confirm = trim($request->input('confirm'));

                if ($model['password'] != md5($model['salt'] . $old)) {
                    return response()->json(['state' => 'error', 'message' => '原密码错误！']);
                } else {
                    if (strlen($new) < 6 || strlen($new) > 15) {
                        return response()->json(['state' => 'error', 'message' => '密码在6 - 15位之间！']);
                    } elseif ($new != $confirm) {
                        return response()->json(['state' => 'error', 'message' => '两次密码不相等！']);
                    } else {
                        $model = UserModel::find($model['id']);
                        $model->salt = str_random('32');
                        $model->password = md5($model->salt . $new);
                        $model->save();
                        return response()->json(['state' => 'success', 'message' => '密码修改成功，请重新登录！']);
                    }
                }
                break;
            case 'bind':
                $qqId = $request->input('qq_id');
                $qqModel = QqUserModel::find($qqId);
                if ($qqModel && $qqModel->user_id) {
                    return response()->json(['state' => 'error', 'message' => '该 QQ 已绑定其他帐号！']);
                }
                $qqModel->user_id = $model->id;
                $qqModel->save();
                return response()->json(['state' => 'success', 'message' => '绑定帐号成功！', 'type' => $type]);
                break;
            default:
                break;
        }
    }
}
