<?php

namespace App\Http\Controllers\Admin\Set;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\ResumeModel;
use App\Helper\SundryHelper;

class ResumeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resumes = ResumeModel::orderBy('rank', 'desc')->paginate(10);
        return view('admin.set.resume.index', compact('resumes'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('name');
        $content = $request->input('content');
        $rank = $request->input('rank');
        if(!$name){
            return response()->json(['state' => 'error', 'message' => '展示名称不能为空！']);
        }
        if(!$content){
            return response()->json(['state' => 'error', 'message' => '展示内容不能为空！']);
        }
        $rank = $rank ? $rank : 99;
        $friend = new ResumeModel();
        $friend->name = $name;
        $friend->content = $content;
        $friend->rank = $rank;
        $friend->save();
        SundryHelper::setResume();
        return response()->json(['state' => 'success', 'message' => '个人信息添加成功！']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $name = $request->input('name');
        $content = $request->input('content');
        $rank = $request->input('rank');
        if(!$name){
            return response()->json(['state' => 'error', 'message' => '展示名称不能为空！']);
        }
        if(!$content){
            return response()->json(['state' => 'error', 'message' => '展示内容不能为空！']);
        }
        $rank = $rank ? $rank : 99;
        ResumeModel::where('id', $id)->update(['name' => $name, 'content' => $content, 'rank' => $rank]);
        SundryHelper::setResume();
        return response()->json(['state' => 'success', 'message' => '个人信息修改成功']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ResumeModel::where('id', $id)->delete();
        SundryHelper::setResume();
        return response()->json(['state' => 'success', 'message' => '个人信息删除成功！']);
    }
}
