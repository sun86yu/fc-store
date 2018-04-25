<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_admin', function (Blueprint $table) {
            $table->increments('id');
            $table->string('admin_name', 45);
            $table->unique('admin_name', 'admin_name');
            $table->string('admin_pwd', 45);
            $table->string('admin_email', 200);
            $table->string('nick_name', 45);
            $table->unsignedTinyInteger('status');
            $table->unsignedSmallInteger('role_id');
            $table->dateTime('create_time');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('t_admin');
    }
}
