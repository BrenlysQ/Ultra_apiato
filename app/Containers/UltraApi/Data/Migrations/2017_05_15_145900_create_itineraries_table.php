<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItinerariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('api_itineraries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('itinerary_id',10);
            $table->string('origin',10);
            $table->string('destination',10);
            $table->date('date_return');
            $table->date('date_departure');
            $table->text('odo');
            $table->text('itinerary');
            $table->text('paxes');
            $table->text('usersatdata');
            $table->integer('usersatid')->unsigned();
            $table->smallInteger('st')->default(0);
            $table->integer('satelite')->unsigned();
            $table->foreign('satelite')->references('id')->on('users');
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
        //
        Schema::drop('billingplus_itineraries');
    }
}
