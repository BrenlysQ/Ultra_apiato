<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_banks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('account_type');
            $table->string('rif');
            $table->string('email');
            $table->mediumText('account_number');
            $table->string('img_url');
            $table->integer('idcurrency')->unsigned();
            $table->foreign('idcurrency')->references('id')->on('api_currencies');
            $table->integer('idstatus')->unsigned();
            $table->foreign('idstatus')->references('id')->on('api_status');
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
        Schema::drop('api_banks');
    }
}
