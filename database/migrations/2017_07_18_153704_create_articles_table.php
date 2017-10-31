<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('articles')) {
            Schema::create('articles', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->comment('用户ID');
                $table->integer('category_id')->comment('分类ID');
                $table->string('title')->comment('标题');
                $table->text('sketch')->comment('简述');
                $table->char('cover', 40)->comment('封面');
                $table->mediumText('content')->comment('正文');
                $table->integer('count')->default(0)->comment('浏览量');
                $table->tinyInteger('sticky')->default(0)->comment('置顶');
                $table->tinyInteger('state')->default(0)->comment('状态');
                $table->softDeletes();
                $table->timestamps();
                $table->index('user_id');
                $table->index('category_id');
                $table->index('count');
                $table->index('sticky');
                $table->index('state');
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
        if (Schema::hasTable('articles')) {
            Schema::dropIfExists('articles');
        }
    }
}
