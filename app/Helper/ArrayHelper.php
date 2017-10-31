<?php

namespace App\Helper;

use Illuminate\Http\Request;

class ArrayHelper
{
    public static function arrayToHtmlMenu($array)
    {
        $html = '<ol class="dd-list">';
        foreach ($array as $value) {
            $html .= '<li class="dd-item dd3-item" data-id="' . $value['id'] . '">';
            $html .= '<div class="dd-handle dd3-handle">Drag</div><div class="dd3-content"><span><i class="' . $value['icon'] . '"></i> ' . $value['name'];
            if ($value['path'] !== 'javascript:void(0);') {
                $html .= "&nbsp;&nbsp;&nbsp;&nbsp;( 链接：" . $value['path'] . " ) ";
            }
            $html .= '</span><span style="float:right;font-weight:normal">';
            $html .= '<a data-toggle="modal" href="#modal-form" class="menu_edit" data-id="' . $value['id'] . '" data-pid="' . $value['pid'] . '" data-name="' . $value['name'] . '" data-path="' . $value['path'] . '" data-icon="' . $value['icon'] . '"> 编辑</a>';
            if (!count($value['children'])) {
                $html .= '<a class="ajax-delete" href="/admin/menu/' . $value['id'] . '" method="delete" confirm="确定删除该菜单吗？"> 删除</a>';
            }
            $html .= '</span></div>';
            if (count($value['children'])) {
                $html .= self::arrayToHtmlMenu($value['children']);
            }
            $html .= '</li>';
        }
        $html .= '</ol>';
        return $html;
    }

    public static function arrayToHtmlCategory($array)
    {
        $html = '<ol class="dd-list">';
        foreach ($array as $value) {
            $html .= '<li class="dd-item dd3-item" data-id="' . $value['id'] . '">';
            $html .= '<div class="dd-handle dd3-handle">Drag</div><div class="dd3-content"><span> ' . $value['name'];
            if ($value['href']) {
                $html .= "&nbsp;&nbsp;&nbsp;&nbsp;( 链接：" . $value['href'] . " ) ";
            }
            if ($value['target']) {
                $html .= "&nbsp;&nbsp;&nbsp;&nbsp;( 新页面打开 ) ";
            }
            $html .= '</span><span style="float:right;font-weight:normal">';
            $html .= '<a data-toggle="modal" href="#modal-form" class="category_edit" data-id="' . $value['id'] . '" data-pid="' . $value['pid'] . '" data-name="' . $value['name'] . '" data-href="' . $value['href'] . '" data-tar="' . $value['target'] . '"> 编辑</a>';
            if (!count($value['children'])) {
                $html .= '<a class="ajax-delete" href="/admin/category/' . $value['id'] . '" method="delete" confirm="确定删除该分类吗？"> 删除</a>';
            }
            $html .= '</span></div>';
            if (count($value['children'])) {
                $html .= self::arrayToHtmlCategory($value['children']);
            }
            $html .= '</li>';
        }
        $html .= '</ol>';
        return $html;
    }

    public static function arrayToTree($array, $pid = 0)
    {
        $tree = array();                                //每次都声明一个新数组用来放子元素
        foreach ($array as $menu) {
            if ($menu['pid'] == $pid) {                      //匹配子记录
                $menu['children'] = self::arrayToTree($array, $menu['id']); //递归获取子记录
                if ($menu['children'] == null) {
                    unset($menu['children']);             //如果子元素为空则unset()进行删除，说明已经到该分支的最后一个元素了（可选）
                }
                $tree[] = $menu;                           //将记录存入新数组
            }
        }
        return $tree;
    }


}
