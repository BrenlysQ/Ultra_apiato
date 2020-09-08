<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('api_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->float('total_amount',12,2);
            $table->float('total_tax',12,2);
            $table->float('total_paid',12,2);
            $table->float('total_base',12,2);
            $table->float('total_fee',8,2);
            $table->text('usersatdata');
            $table->integer('usersatid')->unsigned();
            $table->smallInteger('st')->default(0);
            $table->integer('satelite')->unsigned();
            $table->foreign('satelite')->references('id')->on('users');
            $table->integer('currency')->unsigned();
            $table->foreign('currency')->references('id')->on('api_currencies');
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
        Schema::drop('api_invoices');
    }
}
