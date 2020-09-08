<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCodeResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('password_resets', function (Blueprint $table) {
          //$table->unsigned('currency');
          $table->string('code',10);
          //$table->foreign('idcurrency')->references('id')->on('api_currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pup_payments', function (Blueprint $table) {
            $table->dropColumn('idcurrency');
        });
    }
}
