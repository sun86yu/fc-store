<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cat_name', 45);
            $table->unsignedTinyInteger('cat_level')->default(0);
            $table->unsignedInteger('cat_parent')->default(0);
            $table->unsignedTinyInteger('is_active')->default(1);
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
        Schema::dropIfExists('t_category');
    }
}
