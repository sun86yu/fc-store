<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_tel', 20);
            $table->string('password', 60);
            $table->string('user_mail', 100);
            $table->string('user_name', 20);
            $table->string('reg_ip', 20);
            $table->string('user_identy', 20);
            $table->dateTime('reg_time');
            $table->dateTime('last_login_time');
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('wx_unionid', 500)->nullable();
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
        Schema::dropIfExists('t_user');
    }
}
