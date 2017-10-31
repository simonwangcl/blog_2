<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\ArticleTagRelationModel;
use App\Model\PointModel;
use App\Model\CommentModel;

class ArticleModel extends Model
{
    protected $table = 'articles';
    use SoftDeletes;
    protected $dates = ['deleted_at'];

//     获取文章作者信息。
    public function author()
    {
        return $this->belongsTo('App\Model\UserModel', 'user_id', 'id');
    }

//     获取文章分类。
    public function category()
    {
        return $this->belongsTo('App\Model\CategoryModel', 'category_id', 'id');
    }

//     获取文章下面的所有tags。
    public function tags()
    {
        return $this->belongsToMany('App\Model\TagModel', with(new ArticleTagRelationModel())->getTable(), 'article_id', 'tag_id');
    }

//     获取文章评论。
    public function comments()
    {
        return $this->hasMany('App\Model\CommentModel', 'article_id');
    }

//     获取文章点赞。
    public function points()
    {
        return $this->hasMany('App\Model\PointModel', 'article_id');
    }

    public static function boot()
    {
        parent::boot();

//        软删除就不要这个了
//        self::deleting(function ($model) {
//            ArticleTagRelationModel::where('article_id', $model->id)->delete();
//            CommentModel::where('article_id', $model->id)->delete();
//            PointModel::where('article_id', $model->id)->delete();
//            return TRUE;
//        });
    }
}
