<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_log', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('action_type');
            $table->dateTime('act_time');
            $table->unsignedTinyInteger('is_admin');
            $table->string('action_detail', 200);
            $table->unsignedInteger('target_id');
            $table->index(['is_admin', 'user_id', 'act_time', 'action_type']);
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
        Schema::dropIfExists('t_log');
    }
}
