<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePupStripeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pup_stripe', function (Blueprint $table) {
            $table->increments('id');
            $table->string('charge_id');
            $table->string('status');
            $table->string('amount');
            $table->string('auth_token');
            $table->mediumText('response');
            $table->datetime('date');
            $table->string('card_type');
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
        $table->dropIfExist('pup_stripe');
    }
}