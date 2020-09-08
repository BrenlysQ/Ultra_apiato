<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('api_refunds', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('invoice_id');
          $table->integer('itinerary_id');
          $table->float('price');
          $table->float('balance');
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
        Schema::drop('api_refunds');
    }
}
