<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAirlineBalanceHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('airlines_balance_history', function (Blueprint $table) {
        $table->increments('id');
        $table->string('localizable');
        $table->string('airline_code');
        $table->float('last_balance');
        $table->float('balance');
        $table->integer('currency');
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
        Schema::drop('airlines_balance_history');
    }
}
