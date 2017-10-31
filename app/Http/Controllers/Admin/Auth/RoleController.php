<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Auth\BaseController;
use App\Model\RoleModel;
use App\Helper\ArrayHelper;
use App\Model\UserModel;
use App\Model\MenuModel;
use App\Model\RoleMenuRelationModel;

class RoleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = RoleModel::with(['menus' => function ($query) {
            $query->where('pid', 0);
        }])->paginate(10);
        return view('admin.auth.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('name');
        $len = mb_strlen($name);
        if ($len >= 1 && $len <= 9) {
            if (RoleModel::where('name', $name)->first()) {
                return response()->json(['state' => 'error', 'message' => '角色名称已存在！']);
            } else {
                $role = new RoleModel();
                $role->name = $name;
                $role->save();
                return response()->json(['state' => 'success', 'message' => '角色添加成功！']);
            }
        }
        return response()->json(['state' => 'error', 'message' => '角色名称长度于1 - 9 字符之间！']);
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
        $children = array();
        $role = RoleModel::where('id', $id)->with('menu')->first();
        $menus = MenuModel::where('pid', 0)->with('children')->orderBy('rank')->get();
        if ($role->menu->toArray()) {
            foreach ($role['menu'] as $menu) {
                $children[] = $menu->menu_id;
            }
        }
        $role->menu = $children;
        return view('admin.auth.role.update', compact('role', 'menus'));
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
        $type = $request->input('type');
        if ($type == 'role') {//form表单请求更新角色权限
            $menus_id = $request->input('menus_id', array());
            $menus = RoleMenuRelationModel::where('role_id', $id)->get();
            foreach ($menus as $menu) {
                if (!in_array($menu->menu_id, $menus_id)) {
                    $menu->delete();
                }
                unset($menus_id[$menu->menu_id]);
            }
            if (count($menus_id)) {
                foreach ($menus_id as $menu_id) {
                    $roleMenu = new RoleMenuRelationModel();
                    $roleMenu->role_id = $id;
                    $roleMenu->menu_id = $menu_id;
                    $roleMenu->save();
                }
            }
            return redirect('/admin/role');
        } else {//ajax请求处理角色名称的更新
            $name = $request->input('name');
            $len = mb_strlen($name);
            if ($len >= 1 && $len <= 9) {
                $role = RoleModel::where('name', $name)->first();
                if ($role && $role->id != $id) {
                    return response()->json(['state' => 'error', 'message' => '角色名称已存在！！']);
                } else if ($role && $role->id == $id) {
                    return response()->json(['state' => 'error', 'message' => '角色名称没有改变！！']);;
                } else {
                    RoleModel::where('id', $id)->update(['name' => $name]);
                    return response()->json(['state' => 'success', 'message' => '角色修改成功！']);
                }
            }
            return response()->json(['state' => 'error', 'message' => '角色名称长度于1 - 9 字符之间！']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = RoleModel::find($id);
        if (!$role) {
            return response()->json(['state' => 'error', 'message' => '角色不存在！']);
        }
        if (UserModel::where('role_id', $id)->first()) {
            return response()->json(['state' => 'error', 'message' => '该角色已使用，不允许删除！']);
        }
        if ($role->delete()) {
            return response()->json(['state' => 'success', 'message' => '角色删除成功！']);
        }
        return response()->json(['state' => 'error', 'message' => '角色删除失败！']);
    }

}
