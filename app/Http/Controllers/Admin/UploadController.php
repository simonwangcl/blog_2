<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use App\Helper\ImageHelper;
use App\Helper\FileHelper;

class UploadController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.index.index');
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
        $type = $request->input('type');
        $data = json_decode(stripslashes($request->input('image_data')));//裁剪数据
        $file = $request->file('image');
        $imageType = ['image/jpeg', 'image/gif', 'image/png'];
        if ($file->isValid() && in_array($file->getClientMimeType(), $imageType)) {
            switch ($type) {
                case 'avatar':
                    $fileName = FileHelper::bringFileName($file->getClientOriginalExtension());
                    $path = $file->storeAs('avatars', $fileName);
                    if($path && $data){
                        $newPath = base_path('public/storage/') . $path;
                        ImageHelper::cropImage($newPath, $newPath, $data, $file->getMimeType(),200, 200);
                        return response()->json(['state' => 'success', 'message' => '头像上传成功', 'result' => '/storage/' . $path]);
                    }
                    return response()->json(['state' => 'error', 'message' => '保存图片失败！']);
                case 'cover':
                    $fileName = FileHelper::bringFileName($file->getClientOriginalExtension());
                    $path = $file->storeAs('covers', $fileName);
                    if($path && $data){
                        $newPath = base_path('public/storage/') . $path;
                        ImageHelper::cropImage($newPath, $newPath, $data, $file->getMimeType(),260,160);
                        return response()->json(['state' => 'success', 'message' => '封面上传成功', 'result' => '/storage/' . $path]);
                    }
                    return response()->json(['state' => 'error', 'message' => '保存图片失败！']);
                default:
                    return response()->json(['state' => 'error', 'message' => '上传图片分类错误！']);
            }
        }
        return response()->json(['state' => 'error', 'message' => '请上传有效的图片文件(JPG,JPEG,GIF,PNG格式)！']);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
