<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TagModel extends Model
{
    protected $table = 'tags';

    public function articles()
    {
        return $this->belongsToMany('App\Model\ArticleModel', with(new ArticleTagRelationModel())->getTable(), 'tag_id', 'article_id');
    }

}
