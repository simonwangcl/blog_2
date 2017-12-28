<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateXiaomaimaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('small_business')) {
            Schema::create('small_business', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->decimal('price', 5, 2);
                $table->date('date');
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
        if (Schema::hasTable('small_business')) {
            Schema::dropIfExists('small_business');
        }
    }
}
