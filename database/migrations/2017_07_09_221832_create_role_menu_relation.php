<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleMenuRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('role_menu_relation')) {
            Schema::create('role_menu_relation', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('role_id')->comment('角色表ID');
                $table->integer('menu_id')->comment('菜单表ID');
                $table->timestamps();
                $table->index('role_id');
                $table->index('menu_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('role_menu_relation')) {
            Schema::dropIfExists('role_menu_relation');
        }
    }
}
