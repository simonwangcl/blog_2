<?php

namespace App\Http\Middleware;

use Closure;
use App\Helper\SessionHelper;
use App\Helper\MenuHelper;
use App\Model\RoleModel;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $model = SessionHelper::get();
        if ($model) {
            $menus = MenuHelper::getMenus($model->id);
        }
        if (!$model || !$menus) {
            SessionHelper::forget();
            return redirect('/admin/login');
        }
        SessionHelper::keep();
//        若是管理员则认证通过（也可以通过角色的权限管理）
        if ($model->role_id == RoleModel::$roleAdmin) {
            return $next($request);
        }

        $action = \Route::currentRouteName();
        $route = rtrim('/admin/' . substr($action, 0, strpos($action, '.')), '/');

        foreach ($menus as $menu) {
            if ($menu['path'] == $route) {
                return $next($request);
            }
            if (isset($menu['children']) && is_array($menu['children'])) {
                foreach ($menu['children'] as $child) {
                    if ($child['path'] == $route) {
                        return $next($request);
                    }
                }
            }
        }
        abort(400);//没有权限定义到400错误页面，提示并3s后跳到后台首页
    }
}
