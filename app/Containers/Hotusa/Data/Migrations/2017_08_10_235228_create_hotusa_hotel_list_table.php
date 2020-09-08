<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotusaHotelListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotusa_hotel_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codesthot', 5);
            $table->string('codpobhot', 6);
            $table->string('hot_codigo', 6);
            $table->string('hot_codcobol', 6);
            $table->string('hot_coddup');
            $table->string('hot_afiliacion', 3);
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
        Schema::drop('hotusa_hotel_list');
    }
}
