<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('api_invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->float('fee');
            $table->float('total_amount');
            $table->float('total_base');
            $table->float('total_tax');
            $table->string('invoice');
            $table->text('invoiceable_type');
            $table->integer('invoiceable_id')->unsigned();
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
        Schema::drop('api_invoice_items');        
    }
}
