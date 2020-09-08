<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PufAddFreelancePaymentInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('puf_freelance_bank_info', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('id_freelance');
          $table->string('identification');
          $table->string('routing_number')->nullable();
          $table->string('account_number');
          $table->timestamps();
          $table->softDeletes();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('puf_freelance_bank_info');
    }
}
