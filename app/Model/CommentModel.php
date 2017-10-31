<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CommentModel extends Model
{
    protected $table = 'comment';


    /**
     * 获取用户名称，头像。
     */
    public function user()
    {
        return $this->belongsTo('App\Model\UserModel', 'user_id', 'id')->select('id', 'name', 'image');
    }
}
