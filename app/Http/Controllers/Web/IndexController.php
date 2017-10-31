<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Web\BaseController;

use App\Model\TagModel;
use App\Model\CategoryModel;
use App\Model\ArticleModel;
use App\Model\ArticleTagRelationModel;

class IndexController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category = $request->input('category');
        $tag = $request->input('tag');
        $keywords = $request->input('keywords');
        $message = '';
        $param = array();

        $articles = ArticleModel::where('state', 1);
        if ($keywords) {
            $param['keywords'] = $keywords;
            $articles = $articles->where('title', 'like', '%' . $keywords . '%');
        } elseif ($category) {
            $param['category'] = $category;
            $categoryModel = CategoryModel::find($category);
            if ($categoryModel->pid == 0) {//父级，获取所有子级
                $children = CategoryModel::where('pid', $category)->pluck('id')->toArray();
                array_push($children, $category);
                $articles = $articles->whereIn('category_id', $children);
            } else {//子级
                $articles = $articles->where('category_id', $category);
                $category = CategoryModel::find($category)->pid;
            }
            $param['cate'] = $category;//判断哪个分类添加class="active"
        } elseif ($tag) {
            $param['tag'] = $tag;
            $tagModel = TagModel::find($tag);
            $ids = ArticleTagRelationModel::where('tag_id', $tag)->pluck('article_id');
            $articles = $articles->whereIn('id', $ids);
        } else {

        }
        $articles = $articles->select('id', 'user_id', 'category_id', 'title', 'sketch', 'cover', 'count', 'sticky', 'state', 'created_at');
        $articles = $articles->withCount(['comments', 'points' => function($query){
            $query->where('state', 1);
        }])->with('author', 'tags', 'category')->orderBy('id', 'desc')->paginate(5);
        if($articles->count()){
            foreach ($articles as &$article) {
                $article->author = $article->author->name;
            }
        }else{
            if ($keywords) {
                $message = '抱歉！未找到关于搜索 "'.$keywords.'" 的任何文章！目前只提供标题搜索！';
            } elseif ($category) {
                $message = '抱歉！未找到关于类型 "'.$categoryModel->name.'" 的任何文章！';
            } elseif ($tag) {
                $message = '抱歉！未找到关于类型 "'.$tagModel->name.'" 的任何文章！';
            } else {
                $message = '抱歉！还未发布任何文章！';
            }
        }
        if($message){
            $param['message'] = $message;
        }
        return view('web.index.index', compact('articles', 'param'));
    }
}
