<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('menus')) {
            Schema::create('menus', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('pid')->comment('父级ID');
                $table->string('name', 31)->comment('菜单名称');
                $table->string('path', 51)->default('javascript:void(0);')->comment('链接地址');
                $table->string('icon', 31)->nullable()->comment('菜单图标');
                $table->tinyInteger('state')->default(0)->comment('状态');
                $table->tinyInteger('rank')->default(0)->comment('权重排序');
                $table->timestamps();
                $table->index('pid');
                $table->index('name');
                $table->index('state');
                $table->index('rank');
            });
            $date = date('Y-m-d H:i:s');
            DB::table('menus')->insert([
                ['id' => 1, 'pid' => 0, 'name' => '后台首页', 'icon' => 'fa fa-home', 'rank' => '1', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 2, 'pid' => 0, 'name' => '文章管理', 'icon' => 'fa fa-book', 'rank' => '2', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 3, 'pid' => 0, 'name' => '数据统计', 'icon' => 'fa fa-database', 'rank' => '3', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 4, 'pid' => 0, 'name' => '分类管理', 'icon' => 'fa fa-bars', 'rank' => '4', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 5, 'pid' => 0, 'name' => '标签管理', 'icon' => 'fa fa-tags', 'rank' => '5', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 6, 'pid' => 0, 'name' => '权限管理', 'icon' => 'fa fa-cog', 'rank' => '6', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 7, 'pid' => 6, 'name' => '角色列表', 'rank' => '7', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 8, 'pid' => 6, 'name' => '菜单列表', 'rank' => '8', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 9, 'pid' => 6, 'name' => '用户列表', 'rank' => '9', 'created_at' => $date, 'updated_at' => $date],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('menus')) {
            Schema::dropIfExists('menus');
        }
    }
}
