<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Helper\SessionHelper;
use App\Helper\Qq\QqSessionHelper;
use App\Helper\MenuHelper;
use App\Helper\CategoryHelper;
use App\Helper\ArticleHelper;
use App\Helper\TagHelper;
use App\Helper\CommentHelper;
use App\Helper\SundryHelper;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (substr(\Request::path(), 0, 5) == 'admin') {//后台
//            用户数据
            view()->composer('*', function ($view) {
                $view->with('userModel', SessionHelper::get());
            });
//            用户菜单
            view()->composer('*', function ($view) {
                $view->with('userMenu', MenuHelper::getMenus(SessionHelper::get()['id']));
            });
//            方法名称
            view()->composer('*', function ($view) {
                $view->with('routeName', rtrim('/admin/' . substr(\Route::currentRouteName(), 0, strpos(\Route::currentRouteName(), '.')), '/'));
            });
        } else {//前台
//            用户数据
            view()->composer('*', function ($view) {
                $view->with('userModel', SessionHelper::get() ? : QqSessionHelper::get());
            });
//            所有分类
            view()->composer('*', function ($view) {
                $view->with('categories', CategoryHelper::getCategories());
            });
//            置顶文章
            view()->composer('*', function ($view) {
                $view->with('menuStickies', ArticleHelper::getStickies());
            });
//            热门文章，阅读量最高，每小时更新一次缓存
            view()->composer('*', function ($view) {
                $view->with('menuHotArticles', ArticleHelper::getHotArticles());
            });
//            最新评论
            view()->composer('*', function ($view) {
                $view->with('menuComments', CommentHelper::getComments());
            });
//            所有标签
            view()->composer('*', function ($view) {
                $view->with('menuTags', TagHelper::getTags());
            });
//            友情链接
            view()->composer('*', function ($view) {
                $view->with('friendLink', SundryHelper::getFriendLink());
            });
//            个人简介
            view()->composer('*', function ($view) {
                $view->with('menuResumes', SundryHelper::getResume());
            });
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
