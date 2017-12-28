<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BookModel extends Model
{
    protected $table = 'books';

    /**
     * 获取分类下的具体书籍
     */
    public function children()
    {
        return $this->hasMany('App\Model\BookModel', 'pid', 'id')->orderBy('rank');
    }
}
