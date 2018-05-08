<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->text('pro_info');
            $table->dateTime('create_time');
            $table->tinyInteger('status')->default(0);
            $table->dateTime('finish_time');
            $table->unsignedInteger('real_address_id');
            $table->unsignedInteger('pay_type');
            $table->dateTime('pay_time');
            $table->unsignedDecimal('pay_money');
            $table->string('uuid', 100);
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
        Schema::dropIfExists('t_orders');
    }
}
