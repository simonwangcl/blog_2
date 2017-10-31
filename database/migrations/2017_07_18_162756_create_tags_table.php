<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('tags')) {
            Schema::create('tags', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 31)->comment('标签名称');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('article_tag_relation')) {
            Schema::create('article_tag_relation', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('article_id')->comment('文章ID');
                $table->integer('tag_id')->comment('标签ID');
                $table->index('article_id');
                $table->index('tag_id');
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
        if (Schema::hasTable('tags')) {
            Schema::dropIfExists('tags');
        }
        if (Schema::hasTable('article_tag_relation')) {
            Schema::dropIfExists('article_tag_relation');
        }
    }
}
