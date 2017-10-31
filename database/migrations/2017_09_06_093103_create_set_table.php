<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('friend_link')) {
            Schema::create('friend_link', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 31)->comment('展示名称');
                $table->string('href')->comment('跳转链接');
                $table->tinyInteger('rank')->default(0)->comment('排序');
                $table->timestamps();
                $table->index('rank');
            });
        }
        if (!Schema::hasTable('resume')) {
            Schema::create('resume', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 31)->comment('展示名称');
                $table->string('content')->comment('详细内容');
                $table->tinyInteger('rank')->default(99)->comment('排序');
                $table->timestamps();
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
        if (Schema::hasTable('friend_link')) {
            Schema::dropIfExists('friend_link');
        }
        if (Schema::hasTable('resume')) {
            Schema::dropIfExists('resume');
        }
    }
}
