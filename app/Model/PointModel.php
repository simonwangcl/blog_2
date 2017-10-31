<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PointModel extends Model
{
    protected $table = 'point';

    public static $typeZc = 'zc';
    public static $typeQq = 'qq';
    public static $typeWx = 'wx';
}
