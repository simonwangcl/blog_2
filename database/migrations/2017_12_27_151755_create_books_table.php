<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('books')) {
            Schema::create('books', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('pid')->default(0)->comment('分类ID');
                $table->string('name', '50')->comment('分类名称或书籍名称');
                $table->string('path')->nullable()->comment('书籍保存路径');
                $table->string('size', 10)->nullable()->comment('书籍大小');
                $table->string('cloud')->nullable()->comment('网盘地址');
                $table->string('password', 10)->nullable()->comment('网盘密码');
                $table->string('video')->default(0)->comment('是否有视频');
                $table->integer('download')->default(0)->comment('下载次数');
                $table->smallInteger('rank')->default(999)->comment('权重排序');
                $table->timestamps();
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
        if (Schema::hasTable('books')) {
            Schema::dropIfExists('books');
        }
    }
}
