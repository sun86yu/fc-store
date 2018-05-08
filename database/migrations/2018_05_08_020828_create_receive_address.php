<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiveAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_order_address', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('ad_province');
            $table->unsignedSmallInteger('ad_city');
            $table->string('ad_detail', 500);
            $table->string('rec_name', 45);
            $table->text('rec_phone', 20);
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
        Schema::dropIfExists('t_order_address');
    }
}
