<?php

namespace App\Http\Controllers\Admin;

use App\Helper\SessionHelper;
use App\Model\ArticleTagRelationModel;
use App\Model\RoleModel;
use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use App\Model\ArticleModel;
use App\Model\CategoryModel;
use App\Model\TagModel;
use App\Helper\ArticleHelper;
use App\Helper\TagHelper;

class ArticleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = SessionHelper::get();
        $title = $request->input('title');
        $sticky = $request->input('sticky');
        $state = $request->input('state');

        if($user->role_id == RoleModel::$roleAdmin){
            $articles = ArticleModel::withCount(['comments', 'points' => function($query){
                $query->where('state', 1);
            }])->with(['tags',
                'author' => function($query){
                    $query->select('id', 'name');
                }, 'category' => function($query){
                    $query->select('id', 'name');
                }]);
        }else{
            $articles = ArticleModel::where('user_id', $user->id)->with('tags', 'author');
        }

        if (!is_null($sticky)) {
            $params['sticky'] = $sticky;
            $articles = $articles->where('sticky', $sticky);
        }
        if (!is_null($state)) {
            $params['state'] = $state;
            $articles = $articles->where('state', $state);
        }
        if($title){
            $params['title'] = $title;
            $articles = $articles->where('title', 'like', '%'.$title.'%');
        }
        $articles = $articles->orderBy('id', 'desc')->paginate(10);
        foreach ($articles as &$article){
            $article->author = ucwords($article->author->name);
            $article->category = $article->category->name;
            $title = $article->title;
            $article->title = mb_strlen($title, 'utf8') > 20 ? mb_substr($title, 0, 20 , 'utf8').'...' : $title;
            $article->sticky = $article->sticky == 1 ? '已置顶' : '未置顶';
            $article->state = $article->state == 1 ? '已发布' : '未发布';
            $tags = '';
            foreach($article->tags as $tag){
                $tags .= $tag->name.'，';//这个是中文逗号
            }
            $article->tags = trim($tags, '，');
        }
        return view('admin.article.index', compact('articles', 'params'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = CategoryModel::where('pid', 0)->with('children')->orderBy('rank')->get();
        $tags = TagModel::orderBy('id', 'desc')->get();
        return view('admin.article.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cover = $request->input('cover');
        $title = $request->input('title');
        $sketch = $request->input('sketch');
        $category = $request->input('category');
        $tags = $request->input('tags', array());
        $content = $request->input('content');
        if(!$cover){
            return response()->json(['state' => 'error', 'message' => '请先上传一张封面！']);
        }
        if(!$title){
            return response()->json(['state' => 'error', 'message' => '请填写文章标题！']);
        }
        if(!$sketch){
            return response()->json(['state' => 'error', 'message' => '请填写文章简述！']);
        }
        if(!$category){
            return response()->json(['state' => 'error', 'message' => '请选择文章分类！']);
        }
        if(empty($tags)){
            return response()->json(['state' => 'error', 'message' => '请选择文章标签！']);
        }
        if(!$content){
            return response()->json(['state' => 'error', 'message' => '请填写文章正文！']);
        }
        $userModel = SessionHelper::get();
        $article = new ArticleModel();
        $article->user_id = $userModel->id;
        $article->category_id = $category;
        $article->title = $title;
        $article->sketch = $sketch;
        $article->cover = $cover;
        $article->content = $content;
        $article->save();
        foreach($tags as $tag){
            $relation = new ArticleTagRelationModel();
            $relation->article_id = $article->id;
            $relation->tag_id = $tag;
            $relation->save();
        }
        return response()->json(['state' => 'success', 'message' => '文章添加成功！']);
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
        $article = ArticleModel::with('tags')->find($id);
        $tags = array();
        foreach ($article->tags as $tag) {
            $tags[] = $tag->id;
        }
        $article->tags = $tags;
        $categories = CategoryModel::where('pid', 0)->with('children')->orderBy('rank')->get();
        $tags = TagModel::orderBy('id', 'desc')->get();
        return view('admin.article.update', compact('article', 'categories', 'tags'));
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
        $user = UserModel::find( SessionHelper::get()->id);
        $article = ArticleModel::find($id);
        if ($user->can('update', $article)) {
            $type = $request->input('type');
            switch ($type){
                case "sticky":
                    if($article->sticky == 1){
                        $article->sticky = 0;
                        $word = '未置顶';
                        $message = '取消置顶成功！';
                    }else{
                        $article->sticky = 1;
                        $word = '已置顶';
                        $message = '置顶成功！';
                    }
                    $article->save();
                    ArticleHelper::setStickies();
                    return response()->json(['state' => 'success', 'message' => $message, 'word' => $word]);
                case 'state':
                    if($article->state == 1){
                        $article->state = 0;
                        $word = '未发布';
                        $message = '取消发布成功！';
                    }else{
                        $article->state = 1;
                        $word = '已发布';
                        $message = '发布成功！';
                    }
                    if($article->sticky){
                        ArticleHelper::setStickies();
                    }
                    TagHelper::setTags();
                    $article->save();
                    return response()->json(['state' => 'success', 'message' => $message, 'word' => $word]);
                case 'article':
                    $cover = $request->input('cover');
                    $title = $request->input('title');
                    $sketch = $request->input('sketch');
                    $category = $request->input('category');
                    $tags = $request->input('tags', array());
                    $content = $request->input('content');
                    if(!$cover){
                        return response()->json(['state' => 'error', 'message' => '请先上传一张封面！']);
                    }
                    if(!$title){
                        return response()->json(['state' => 'error', 'message' => '请填写文章标题！']);
                    }
                    if(!$sketch){
                        return response()->json(['state' => 'error', 'message' => '请填写文章简述！']);
                    }
                    if(!$category){
                        return response()->json(['state' => 'error', 'message' => '请选择文章分类！']);
                    }
                    if(empty($tags)){
                        return response()->json(['state' => 'error', 'message' => '请选择文章标签！']);
                    }
                    if(!$content){
                        return response()->json(['state' => 'error', 'message' => '请填写文章正文！']);
                    }
                    $oldRelation = ArticleTagRelationModel::where('article_id', $id)->get();

                    $article->category_id = $category;
                    $article->title = $title;
                    $article->sketch = $sketch;
                    $article->cover = $cover;
                    $article->content = $content;
                    $article->save();
                    if ($oldRelation->toArray()) {
                        foreach ($oldRelation as $relation) {
                            if (in_array($relation->tag_id, $tags)) {
                                unset($tags[array_search($relation->tag_id, $tags)]);
                            } else {
                                $relation->delete();
                            }
                        }
                    }
                    if(!empty($tags)){
                        foreach ($tags as $tag) {
                            $relation = new ArticleTagRelationModel();
                            $relation->article_id = $id;
                            $relation->tag_id = $tag;
                            $relation->save();
                        }
                    }
                    return response()->json(['state' => 'success', 'message' => '文章修改成功！']);
                default:
                    return response()->json(['state' => 'error', 'message' => '类型错误！']);
            }
        }else{
            return response()->json(['state' => 'error', 'message' => '没有权限修改该文章！']);
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
        $user = SessionHelper::get();
        $article = ArticleModel::find($id);
        if ($user->can('update', $article)) {
            if($article->sticky){
                ArticleHelper::setStickies();
            }
            if ($article->delete()) {
                return response()->json(['state' => 'success', 'message' => '文章删除成功！']);
            }
            return response()->json(['state' => 'error', 'message' => '文章删除失败！']);
        }else{
            return response()->json(['state' => 'error', 'message' => '没有权限删除该文章！']);
        }

    }
}
