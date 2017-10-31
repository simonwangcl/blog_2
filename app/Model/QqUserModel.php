<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QqUserModel extends Model
{
    protected $table = 'qq_user';

    public function user()
    {
        return $this->belongsTo('App\Model\UserModel', 'user_id', 'id');
    }

}
