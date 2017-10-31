<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;

class UserModel extends Model
{
    protected $table = 'users';
    use Authorizable;

    public function role()
    {
        return $this->belongsTo('App\Model\RoleModel', 'role_id', 'id');
    }

    public function qq()
    {
        return $this->hasOne('App\Model\QqUserModel', 'user_id', 'id');
    }
}
