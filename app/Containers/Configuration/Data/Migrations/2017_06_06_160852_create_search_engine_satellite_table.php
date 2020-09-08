<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchEngineSatelliteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_search_engine_user', function (Blueprint $table) {
              $table->integer('iduser')->unsigned();
              $table->integer('idsearch_engine')->unsigned();
              $table->timestamps();
        });

        Schema::table('api_search_engine_user', function (Blueprint $table) {
            $table->foreign('iduser')->references('id')->on('users');
            $table->foreign('idsearch_engine')->references('id')->on('api_search_engine');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_search_engine_user');
    }
}
