<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/vue', function () {
    return view('vue');
});


Route::any('/mail', 'TestController@index');

Route::group(['prefix' => 'tools', 'namespace' => 'Tools'], function () {
//    复制html代码新页面打开
    Route::get('/html', 'HtmlController@index');
    Route::post('/html/new', 'HtmlController@newPage');
});

Route::group(['namespace' => 'Web'], function () {
//    首页
    Route::get('/', 'IndexController@index');
//    文章详情
    Route::resource('/post', 'PostController');
//    个人简介
    Route::resource('/about', 'AboutController');
//    书籍
    Route::resource('/book', 'BookController');
//    qq登录，退出
    Route::get('/qq', 'QqController@index');
    Route::get('/qq/login', 'QqController@login');
    Route::get('/qq/loginout', 'QqController@loginOut');

});


Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
//    登录
    Route::resource('/login', 'LoginController', ['only' => ['index', 'store', 'destroy']]);
//    注册
    Route::resource('/register', 'RegisterController', ['only' => ['index', 'store']]);
//    文件上传
    Route::resource('/upload', 'UploadController');


    Route::group(['middleware' => 'adminAuth'], function () {
//        后台首页
        Route::resource('/', 'IndexController');
//        个人资料
        Route::resource('/info', 'InfoController', ['only' => ['index', 'show', 'update']]);
//        文章管理
        Route::resource('/article', 'ArticleController');
//        数据统计
        Route::resource('/stats', 'StatsController');

//        文章配置（分类管理，标签管理）
        Route::resource('/category', 'CategoryController');
        Route::resource('/tag', 'TagController', ['only' => ['index', 'store']]);
//        权限管理（角色管理，菜单管理，用户管理）
        Route::group(['namespace' => 'Auth'], function () {
            Route::resource('/role', 'RoleController');
            Route::resource('/menu', 'MenuController');
            Route::resource('/user', 'UserController');
            Route::resource('/qquser', 'QqUserController');
        });
//        系统设置（友情链接，个人简介）
        Route::group(['namespace' => 'Set'], function () {
            Route::resource('/friend', 'FriendController');
            Route::resource('/resume', 'ResumeController');
        });
//        工具（小买卖）
        Route::group(['prefix' => 'tools', 'namespace' => 'Tools'], function () {
            Route::resource('/small', 'SmallController');
            Route::resource('/book', 'BookController');
        });
    });
});