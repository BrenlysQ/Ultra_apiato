<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('pup_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idinvoice')->unsigned();
            $table->dateTime('payment_date')->nullable();
            $table->float('amount',12,2);
            $table->integer('satellite')->unsigned();
            $table->integer('sat_userid')->unsigned();
            $table->text('sat_userdata');
            $table->smallInteger('st');
            $table->smallInteger('payment_gateway');
            $table->integer('processedby')->unsigned();
            $table->foreign('satellite')->references('id')->on('users');
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
        Schema::drop('pup_payments');
    }
}
