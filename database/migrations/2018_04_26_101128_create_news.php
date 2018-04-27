<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_news', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 200);
            $table->string('head_img', 200)->nullable();
            $table->text('content')->nullable();
            $table->dateTime('create_time');
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedTinyInteger('is_galarry')->default(0);
            $table->unsignedTinyInteger('is_link')->default(0);
            $table->string('link_url', 200)->nullable();
            $table->index(['status', 'is_galarry', 'is_link']);
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
        Schema::dropIfExists('t_news');
    }
}
