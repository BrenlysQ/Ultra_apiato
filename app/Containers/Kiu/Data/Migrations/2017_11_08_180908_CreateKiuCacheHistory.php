<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKiuCacheHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kiu_fares_cache_history', function (Blueprint $table) {
            $table->increments('id');
            $table->date('expirationdate');
            $table->string('footprint', 35);
            $table->string('passengertype', 4);
            $table->string('class', 2);
            $table->double('totalfare', 9, 2);
            $table->integer('currency')->unsigned();
            $table->text('airpricinginfo');
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
        Schema::drop('kiu_fares_cache_history');
    }
}
