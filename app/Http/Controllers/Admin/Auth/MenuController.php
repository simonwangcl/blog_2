<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Auth\BaseController;
use App\Helper\ArrayHelper;
use App\Model\MenuModel;
use App\Model\RoleMenuRelationModel;

class MenuController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $names = array();
        $html = '';
        $menus = MenuModel::where('pid', 0)->with('children')->orderBy('rank')->get();
        if ($menus->toArray()) {
            foreach ($menus as $menu) {
                $names[$menu['id']] = $menu['name'];
            }
            $html .= ArrayHelper::arrayToHtmlMenu($menus);
        }
        return view('admin.auth.menu.index', compact('html', 'names'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $pid = $request->input('pid');
        $path = $request->input('path');
        $icon = $request->input('icon');
        $len = mb_strlen($name);

        if ($pid) {
            $menu = MenuModel::where('id', $pid)->first();
            if (!$menu) {
                $pid = 0;
            }
        }
        if ($len >= 1 && $len <= 9) {
            if (MenuModel::where('name', $name)->first()) {
                return response()->json(['state' => 'error', 'message' => '菜单名称已存在！']);
            } else {
                $menu = new MenuModel();
                $menu->pid = $pid;
                $menu->name = $name;
                if ($path) {
                    $menu->path = $path;
                }
                if ($icon) {
                    $menu->icon = $icon;
                }
                $menu->save();
                return response()->json(['state' => 'success', 'message' => '菜单添加成功！']);
            }
        }
        return response()->json(['state' => 'error', 'message' => '菜单名称长度于1 - 9 字符之间！']);
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
        //
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

        if ($type == 'rank') {
            $list = json_decode($request->input('list'), true);
            if (is_array($list)) {
                $i = 1;
                foreach ($list as $menu) {
                    foreach ($menu as $value) {
                        if (is_integer($value)) {
                            MenuModel::where('id', $menu['id'])->update(['pid' => 0, 'rank' => $i]);
                            ++$i;
                        }
                        if (is_array($value)) {
                            foreach ($value as $child) {
                                MenuModel::where('id', $child['id'])->update(['pid' => $menu['id'], 'rank' => $i]);
                                ++$i;
                            }
                        }
                    }
                }
            }
            return response()->json(['state' => 'success', 'message' => '菜单排序修改成功！']);
        } else {
            $name = $request->input('name');
            $path = $request->input('path');
            $icon = $request->input('icon');
            $len = mb_strlen($name);

            if ($len >= 1 && $len <= 9) {
                $menu = MenuModel::where('name', $name)->first();
                if ($menu && $menu->id != $id) {
                    return response()->json(['state' => 'error', 'message' => '菜单名称已存在！']);
                } else {
                    if (!$menu) {
                        $menu = MenuModel::find($id);
                    }
                    $menu->name = $name;
                    if ($path) {
                        $menu->path = $path;
                    } else {
                        $menu->path = 'javascript:void(0);';
                    }
                    $menu->icon = $icon;
                    $menu->save();
                    return response()->json(['state' => 'success', 'message' => '菜单修改成功！']);
                }
            }
            return response()->json(['state' => 'error', 'message' => '菜单名称长度于1 - 9 字符之间！']);
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
        $menu = MenuModel::find($id);
        if (!$menu) {
            return response()->json(['state' => 'error', 'message' => '菜单不存在！']);
        }
        if (RoleMenuRelationModel::where('menu_id', $id)->first()) {
            return response()->json(['state' => 'error', 'message' => '菜单正在使用中，不允许删除！']);
        }
        if ($menu->delete()) {
            return response()->json(['state' => 'success', 'message' => '菜单删除成功！']);
        }
        return response()->json(['state' => 'error', 'message' => '菜单删除失败！']);
    }
}
