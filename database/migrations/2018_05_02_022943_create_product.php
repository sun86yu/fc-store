<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_product', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pro_name', 100);
            $table->text('pro_img')->nullable();
            $table->text('content')->nullable();
            $table->dateTime('create_time');
            $table->unsignedInteger('cat_id');
            $table->text('info');
            $table->unsignedTinyInteger('status')->default(1);
            $table->decimal('price');
            $table->unsignedInteger('remain_cnt');
            $table->unsignedInteger('saled_cnt')->default(0);
            $table->index(['status', 'cat_id', 'saled_cnt']);
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
        Schema::dropIfExists('t_product');
    }
}
