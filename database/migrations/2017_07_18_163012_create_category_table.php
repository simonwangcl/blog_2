<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('pid')->comment('父类ID');
                $table->string('name', 31)->comment('分类名称');
                $table->string('href', 51)->nullable()->comment('跳转链接');
                $table->tinyInteger('target')->default(0)->comment('是否新页面打开');
                $table->tinyInteger('rank')->default(0)->comment('权重排序');
                $table->timestamps();
                $table->index('pid');
                $table->index('rank');
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
        if (Schema::hasTable('categories')) {
            Schema::dropIfExists('categories');
        }
    }
}
