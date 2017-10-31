<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use App\Model\CategoryModel;
use App\Helper\ArrayHelper;
use App\Helper\CategoryHelper;

class CategoryController extends BaseController
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
        $categories = CategoryModel::where('pid', 0)->with('children')->orderBy('rank')->get();

        if ($categories->toArray()) {
            foreach ($categories as $category) {
                $names[$category['id']] = $category['name'];
            }
            $html .= ArrayHelper::arrayToHtmlCategory($categories);
        }
        return view('admin.category.index', compact('html', 'names'));
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
        $pid = $request->input('pid');
        $name = $request->input('name');
        $href = $request->input('href');
        $target = $request->input('target');
        $len = mb_strlen($name);

        if ($pid) {
            $category = CategoryModel::where('id', $pid)->first();
            if (!$category) {
                $pid = 0;
            }
        }
        if ($len >= 1 && $len <= 9) {
            if (CategoryModel::where('name', $name)->first()) {
                return response()->json(['state' => 'error', 'message' => '分类名称已存在！']);
            } else {
                $category = new CategoryModel();
                $category->pid = $pid;
                $category->name = $name;
                $category->href = $href;
                $category->target = $target;
                $category->save();
                CategoryHelper::setCategory();
                return response()->json(['state' => 'success', 'message' => '分类添加成功！']);
            }
        }
        return response()->json(['state' => 'error', 'message' => '分类名称长度于1 - 9 字符之间！']);
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
                foreach ($list as $category) {
                    foreach ($category as $value) {
                        if (is_integer($value)) {
                            CategoryModel::where('id', $category['id'])->update(['pid' => 0, 'rank' => $i]);
                            ++$i;
                        }
                        if (is_array($value)) {
                            foreach ($value as $child) {
                                CategoryModel::where('id', $child['id'])->update(['pid' => $category['id'], 'rank' => $i]);
                                ++$i;
                            }
                        }
                    }
                }
            }
            CategoryHelper::setCategory();
            return response()->json(['state' => 'success', 'message' => '分类排序修改成功！']);
        } else {
            $name = $request->input('name');
            $href = $request->input('href');
            $target = $request->input('target');
            $len = mb_strlen($name);

            if ($len >= 1 && $len <= 9) {
                $category = CategoryModel::where('name', $name)->first();
                if ($category && $category->id != $id) {
                    return response()->json(['state' => 'error', 'message' => '分类名称已存在！']);
                } else {
                    if (!$category) {
                        $category = CategoryModel::find($id);
                    }
                    $category->name = $name;
                    $category->href = $href;
                    $category->target = $target;
                    $category->save();
                    CategoryHelper::setCategory();
                    return response()->json(['state' => 'success', 'message' => '分类修改成功！']);
                }
            }
            return response()->json(['state' => 'error', 'message' => '分类名称长度于1 - 9 字符之间！']);
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
        $category = CategoryModel::find($id);
        if (!$category) {
            return response()->json(['state' => 'error', 'message' => '分类不存在！']);
        }
//        if (RoleMenuRelationModel::where('menu_id', $id)->first()) {
//            return response()->json(['state' => 'error', 'message' => '分类正在使用中，不允许删除！']);
//        }
        if ($category->delete()) {
            CategoryHelper::setCategory();
            return response()->json(['state' => 'success', 'message' => '分类删除成功！']);
        }
        return response()->json(['state' => 'error', 'message' => '分类删除失败！']);
}
}
