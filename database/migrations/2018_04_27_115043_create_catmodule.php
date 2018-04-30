<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatmodule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_category_module', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cat_id')->default(0);
            $table->string('mod_name', 45);
            $table->unsignedTinyInteger('mod_type')->default(1);
            $table->string('mod_dw', 45)->nullable();
            $table->string('default_value', 45)->nullable();
            $table->string('mod_en_name', 45);
            $table->unsignedTinyInteger('is_number')->default(0);
            $table->unsignedTinyInteger('min_length')->default(1);
            $table->unsignedTinyInteger('max_length')->default(10);
            $table->unsignedTinyInteger('is_phone')->default(0);
            $table->unsignedTinyInteger('is_email')->default(0);
            $table->unsignedTinyInteger('is_date')->default(0);
            $table->unsignedTinyInteger('is_active')->default(1);
            $table->index(['cat_id']);
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
        Schema::dropIfExists('t_category_module');
    }
}
