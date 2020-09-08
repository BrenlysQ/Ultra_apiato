<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotusaDirectoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotusa_directories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('province_name',100);
            $table->string('province_code',5);
            $table->string('country_code',2);
            $table->string('hotel_name',255);
            $table->string('cobol_code',8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hotusa_directories');
    }
}
