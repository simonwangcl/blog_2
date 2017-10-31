<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Model\RoleModel;

class CreateRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 31)->comment('角色名称');
                $table->timestamps();
                $table->unique('name');
            });
            $date = date('Y-m-d H:i:s');
            DB::table('roles')->insert([
                ['id' => 1, 'name' => '管理员', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 2, 'name' => '作者', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 3, 'name' => '游客', 'created_at' => $date, 'updated_at' => $date]
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
        if (Schema::hasTable('roles')) {
            Schema::dropIfExists('roles');
        }
    }
}
