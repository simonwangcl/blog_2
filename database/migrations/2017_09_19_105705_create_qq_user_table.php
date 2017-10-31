<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQqUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('qq_user')) {
            Schema::create('qq_user', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('open_id', 32);
                $table->string('name', 51);
                $table->char('gender', 1)->comment('f女，m男，n未知');
                $table->string('province', 31);
                $table->string('city', 31);
                $table->smallInteger('year');
                $table->string('image')->comment('40px头像');
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
        if (Schema::hasTable('qq_user')) {
            Schema::dropIfExists('qq_user');
        }
    }
}
