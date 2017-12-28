<?php

namespace App\Http\Controllers\Admin\Tools;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\SessionHelper;
use App\Model\SmallModel;

class SmallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = SessionHelper::get();

        $today = SmallModel::where('user_id', $user['id'])->where('date',date('Y-m-d'))->count();
        $seven = SmallModel::where('user_id', $user['id'])->where('date','>',date('Y-m-d', strtotime('-7 days')))->count();
        $thirty = SmallModel::where('user_id', $user['id'])->where('date','>',date('Y-m-d', strtotime('-30 days')))->count();
        $all_num = SmallModel::where('user_id', $user['id'])->count();
        $all_price = SmallModel::where('user_id', $user['id'])->sum('price');
        $smalls = SmallModel::where('user_id', $user['id'])->orderBy('date', 'desc')->paginate(10);

        return view('admin.tools.small.index', compact('smalls', 'today', 'seven', 'thirty', 'all_num','all_price'));
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
        $price = $request['price'];
        $date = $request['date'];
        $user = SessionHelper::get();

        if (!is_numeric($price)) {
            return response()->json(['state' => 'error', 'message' => '金额必须位数字！']);
        }

        $model = new SmallModel();
        $model->user_id = $user['id'];
        $model->price = $price;
        $model->date = $date ?: date('Y-m-d');
        $model->save();

        return response()->json(['state' => 'success', 'message' => '添加订单成功！']);
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
        $price = $request['price'];
        $date = $request['date'];
        $user = SessionHelper::get();

        if (!is_numeric($price)) {
            return response()->json(['state' => 'error', 'message' => '金额必须位数字！']);
        }

        $model = SmallModel::find($id);

        if (!$model) {
            return response()->json(['state' => 'error', 'message' => '订单不存在！']);
        }

        $model->user_id = $user['id'];
        $model->price = $price;
        $model->date = $date ?: date('Y-m-d');
        $model->save();

        return response()->json(['state' => 'success', 'message' => '修改订单成功！']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = SessionHelper::get();

        $model = SmallModel::find($id);

        if (!$model) {
            return response()->json(['state' => 'error', 'message' => '订单不存在！']);
        }

        if ($model['user_id'] != $user['id']) {
            return response()->json(['state' => 'error', 'message' => '没有权限修改该订单！']);
        }

        $model->delete();

        return response()->json(['state' => 'success', 'message' => '订单删除成功！']);
    }
}
