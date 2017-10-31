<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Helper\Qq\QqSessionHelper;
use App\User;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Auth\BaseController;
use App\Model\UserModel;
use App\Model\RoleModel;
use App\Helper\ImageHelper;
use App\Helper\Qq\QqUserHelper;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $role = $request->input('role');
        $users = UserModel::with('role');
        $params = array();
        if($keyword){
            $params['keyword'] = $keyword;
            $users = $users->where(function($query) use($keyword){
                return $query->where('name', 'like', '%'.$keyword.'%')
                    ->orWhere('email', 'like', '%'.$keyword.'%')
                    ->orWhere('phone', 'like', '%'.$keyword.'%');
            });
        }
        if($role){
            $params['role'] = $role;
            $users = $users->where('role_id', $role);
        }
        $users = $users->paginate(10);
        $roles = RoleModel::all();
        return view('admin.auth.user.index', compact('users', 'roles', 'params'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = RoleModel::all();
        return view('admin.auth.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $avatar = $request->input('avatar');
        $username = $request->input('username');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $password = $request->input('password');
        $role_id = $request->input('role_id', 3);
        if (!$avatar) {
            return response()->json(['state' => 'error', 'message' => '请先上传头像！']);
        }
        if (!$username || strlen($username) < 2 || strlen($username) > 30) {
            return response()->json(['state' => 'error', 'message' => '用户名 2 - 30 字节之间(一个中文3个字节)！']);
        }
        if (UserModel::where('name', $username)->first()) {
            return response()->json(['state' => 'error', 'message' => '用户名已存在！']);
        }
        if (!$email) {
            return response()->json(['state' => 'error', 'message' => '邮箱地址不能为空！']);
        }
        if (UserModel::where('email', $email)->first()) {
            return response()->json(['state' => 'error', 'message' => '邮箱地址已存在！']);
        }
        if (!$phone) {
            return response()->json(['state' => 'error', 'message' => '手机号码不能为空！']);
        }
        if (UserModel::where('phone', $phone)->first()) {
            return response()->json(['state' => 'error', 'message' => '手机号码已存在！']);
        }
        if (strlen($password) < 5 && strlen($password) > 15) {
            return response()->json(['state' => 'error', 'message' => '密码为 5 - 15 位的字符串！']);
        }

        $salt = str_random('32');
        $userModel = new UserModel();
        $userModel->image = $avatar;
        $userModel->name = $username;
        $userModel->email = $email;
        $userModel->phone = $phone;
        $userModel->salt = $salt;
        $userModel->password = md5($salt . $password);
        $userModel->role_id = $role_id;
        $userModel->save();
        if ($userModel->id) {
            return response()->json(['state' => 'success', 'message' => '用户创建成功！']);
        }
        return response()->json(['state' => 'error', 'message' => '用户创建失败！']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = UserModel::find($id);
        $roles = RoleModel::all();
        return view('admin.auth.user.update', compact('roles', 'model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = UserModel::find($id);
        $avatar = $request->input('avatar');
        $username = $request->input('username');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $password = $request->input('pass');
        $role_id = $request->input('role_id', 3);
        if (!$avatar) {
            return response()->json(['state' => 'error', 'message' => '请先上传头像！']);
        }
        if (!$username || strlen($username) < 2 || strlen($username) > 30) {
            return response()->json(['state' => 'error', 'message' => '用户名 2 - 30 字节之间(一个中文3个字节)！']);
        }
        $userModel = UserModel::where('name', $username)->first();
        if ($userModel && $userModel->id != $model->id) {
            return response()->json(['state' => 'error', 'message' => '用户名已存在！']);
        }
        if (!$email) {
            return response()->json(['state' => 'error', 'message' => '邮箱地址不能为空！']);
        }
        $userModel = UserModel::where('email', $email)->first();
        if ($userModel && $userModel->id != $model->id) {
            return response()->json(['state' => 'error', 'message' => '邮箱地址已存在！']);
        }
        if (!$phone) {
            return response()->json(['state' => 'error', 'message' => '手机号码不能为空！']);
        }
        $userModel = UserModel::where('phone', $phone)->first();
        if ($userModel && $userModel->id != $model->id) {
            return response()->json(['state' => 'error', 'message' => '手机号码已存在！']);
        }
        if ($password && strlen($password) < 5 && strlen($password) > 15) {
            return response()->json(['state' => 'error', 'message' => '密码为 5 - 15 位的字符串！']);
        }
        unset($userModel);
        if($model->image != $avatar){
            ImageHelper::deleteImage($model->image, 'avatar');
            $model->image = $avatar;
        }
        $model->name = $username;
        $model->email = $email;
        $model->phone = $phone;
        if ($password) {
            $model->salt = str_random('32');
            $model->password = md5($model->salt . $password);
        }
        $model->role_id = $role_id;
        $model->save();
        return response()->json(['state' => 'success', 'message' => '用户数据更新成功！']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        UserModel::find($id)->delete();
        QqUserHelper::where('user_id', $id)->update(['user_id'=> NULL]);
        return response()->json(['state' => 'success', 'message' => '用户删除成功！']);
    }
}
