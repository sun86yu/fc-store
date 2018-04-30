<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatconst extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_category_const', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('mod_id');
            $table->string('const_text', 45);
            $table->unsignedInteger('const_val')->default(0);
            $table->unsignedInteger('show_order')->default(1);
            $table->index(['mod_id', 'show_order']);
            $table->unique(['mod_id', 'const_val']);
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
        Schema::dropIfExists('t_category_const');
    }
}
