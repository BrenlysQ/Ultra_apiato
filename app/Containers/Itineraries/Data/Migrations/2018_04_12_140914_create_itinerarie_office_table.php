<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItinerarieOfficeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('office_itineraries', function (Blueprint $table) {
          $table->increments('id');
          $table->string('localizable')->unique();
          $table->integer('currency');
          $table->string('seller_email');
          $table->integer('status')->default(1);
          $table->timestamps();
          $table->softDeletes();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('office_itineraries');
    }
}
