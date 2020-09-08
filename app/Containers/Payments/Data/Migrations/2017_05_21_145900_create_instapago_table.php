<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstapagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('pup_instapago', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('success')->default(0);
            $table->string('insta_id', 80)->nullable();
            $table->unsignedInteger('insta_reference')->nullable();
            $table->text('insta_voucher')->nullable();
            $table->text('insta_response')->nullable();
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
        Schema::drop('pup_bank_transfers');
    }
}
