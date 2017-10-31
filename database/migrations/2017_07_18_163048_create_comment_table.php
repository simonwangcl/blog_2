<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('comment')) {
            Schema::create('comment', function (Blueprint $table) {
                $table->increments('id');
                $table->char('user_type', 2)->comment('评论用户表');
                $table->integer('user_id')->comment('评论用户ID');
                $table->integer('article_id')->comment('文章ID');
                $table->char('master_type', 2)->comment('楼主表');
                $table->integer('master_id')->default(0)->comment('楼主ID');
                $table->string('content')->comment('评论内容');
                $table->timestamps();
                $table->index('article_id');
            });
        }
        if (!Schema::hasTable('point')) {
            Schema::create('point', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('article_id')->comment('文章ID');
                $table->integer('user_id')->comment('用户ID');
                $table->char('type',2)->comment('类型');
                $table->tinyInteger('state')->comment('1点赞，0取消点赞');
                $table->timestamps();
                $table->index(['article_id', 'user_id', 'state']);
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
        if (Schema::hasTable('comment')) {
            Schema::dropIfExists('comment');
        }
        if (Schema::hasTable('point')) {
            Schema::dropIfExists('point');
        }
    }
}
