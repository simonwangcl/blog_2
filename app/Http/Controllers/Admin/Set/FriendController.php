<?php

namespace App\Http\Controllers\Admin\Set;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\FriendLinkModel;
use App\Helper\ValidateHelper;
use App\Helper\SundryHelper;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $friends = FriendLinkModel::paginate(10);
        return view('admin.set.friend.index', compact('friends'));
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
        $href = $request->input('href');
        $rank = $request->input('rank');
        if(!$name){
            return response()->json(['state' => 'error', 'message' => '展示名称不能为空！']);
        }
        if(!ValidateHelper::isUrl($href)){
            return response()->json(['state' => 'error', 'message' => '请填写正确的跳转地址，如：http://www.baidu.com']);
        }
        $rank = $rank ? $rank : 0;
        $friend = new FriendLinkModel();
        $friend->name = $name;
        $friend->href = $href;
        $friend->rank = $rank;
        $friend->save();
        SundryHelper::setFriendLink();
        return response()->json(['state' => 'success', 'message' => '友链添加成功！']);
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
        $name = $request->input('name');
        $href = $request->input('href');
        $rank = $request->input('rank');
        if(!$name){
            return response()->json(['state' => 'error', 'message' => '展示名称不能为空！']);
        }
        if(!ValidateHelper::isUrl($href)){
            return response()->json(['state' => 'error', 'message' => '请填写正确的跳转地址，如：http://www.baidu.com']);
        }
        $rank = $rank ? $rank : 0;
        FriendLinkModel::where('id', $id)->update(['name' => $name, 'href' => $href, 'rank' => $rank]);
        SundryHelper::setFriendLink();
        return response()->json(['state' => 'success', 'message' => '友链修改成功']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FriendLinkModel::where('id', $id)->delete();
        SundryHelper::setFriendLink();
        return response()->json(['state' => 'success', 'message' => '友链删除成功！']);
    }
}
