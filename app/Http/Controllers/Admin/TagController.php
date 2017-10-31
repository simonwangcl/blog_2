<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use App\Model\TagModel;
use App\Helper\TagHelper;

class TagController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = '';
        $tagModel = TagModel::pluck('name');
        if ($tagModel->toArray()) {
            foreach ($tagModel as $tag) {
                $tags .= '"' . $tag . '",';
            }
        }
        $tags = trim($tags, ',');
        return view('admin.tag.index', compact('tags'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newTags = explode(',', $request->input('tag'));
        $oldTags = TagModel::all();

        if ($oldTags->toArray()) {
            foreach ($oldTags as $tag) {
                if (in_array($tag->name, $newTags)) {
                    unset($newTags[array_search($tag->name, $newTags)]);
                } else {
                    $tag->delete();
                }
            }
        }
        if (!empty($newTags)) {
            foreach ($newTags as $tag) {
                $tagModel = new TagModel();
                $tagModel->name = $tag;
                $tagModel->save();
            }
        }
        TagHelper::setTags();
        return response()->json(['state' => 'success', 'message' => '标签修改成功！']);
    }
}
