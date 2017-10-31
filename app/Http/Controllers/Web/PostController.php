<?php

namespace App\Http\Controllers\Web;

use App\Helper\ArticleHelper;
use App\Helper\CommentHelper;
use App\Model\PointModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Web\BaseController;
use App\Model\ArticleModel;
use App\Helper\SessionHelper;
use App\Helper\Qq\QqSessionHelper;
use App\Helper\IpHelper;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $point = false;
        if (!ArticleHelper::checkForIp($id)) {
            ArticleModel::where('id', $id)->increment('count');
        }
        $user = SessionHelper::get();
        if ($user) {
            $type = PointModel::$typeZc;
        } else {
            $user = QqSessionHelper::get();
            $type = PointModel::$typeQq;
        }
        $article = ArticleModel::where('state', 1)->with(['author' => function ($query) {
            $query->select('id', 'name');
        }, 'tags', 'comments',
            'points' => function ($query) {
                $query->where('state', 1);
            },
            'category' => function ($query) {
                $query->select('id', 'name');
            }])->find($id);
        if(!$article){
            return redirect('/');
        }
        $article->points = $article->points->count();
        if ($user) {
            $point = ArticleHelper::checkPoint($user->id, $article->id, $type);
        }
        $show = false;
        if(IpHelper::getUserIp() == '115.199.48.157'){
            $show = true;


        }
        return view('web.post.index', compact('article', 'point', 'show'));
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
        $user = SessionHelper::get();
        if ($user) {
            $type = PointModel::$typeZc;
        } else {
            $user = QqSessionHelper::get();
            $type = PointModel::$typeQq;
        }

        $point = PointModel::where('user_id', $user->id)->where('article_id', $id)->where('type', $type)->first();
        if ($point) {
            if ($point->state == 1) {
                $point->state = 0;
                $point->save();
                $result = 1;
            } else {
                $point->state = 1;
                $point->save();
                $result = 0;
            }
        } else {
            $point = new PointModel();
            $point->user_id = $user->id;
            $point->article_id = $id;
            $point->type = $type;
            $point->state = 1;
            $point->save();
            $result = 0;
        }
        return response()->json(['state' => 'success', 'result' => $result]);
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
