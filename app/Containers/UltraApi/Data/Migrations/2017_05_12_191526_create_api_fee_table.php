<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiFeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('api_user_fee', function (Blueprint $table) {
            $table->increments('id');
            $table->float('fee',8,2);
            $table->smallInteger('plusultra')->default(0);
            $table->smallInteger('type');
            $table->integer('currency')->unsigned();
            $table->foreign('currency')->references('id')->on('api_currencies');
            $table->integer('user')->unsigned();
            $table->foreign('user')->references('id')->on('users');
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
        Schema::drop('api_user_fee');
    }
}
