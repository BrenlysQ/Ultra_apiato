<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaguelofacilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pup_paguelofacil', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codoper');
            $table->string('status');
            $table->string('amount');
            $table->string('auth_token');
            $table->mediumText('response');
            $table->string('date',6);
            $table->string('cardtype',4);
            $table->string('time',6);
            $table->string('name');
            $table->string('lastname');
            $table->string('email');
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
        Schema::drop('pup_paguelofacil');
    }
}
