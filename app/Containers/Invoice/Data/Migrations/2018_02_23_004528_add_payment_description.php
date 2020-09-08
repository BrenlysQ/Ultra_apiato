<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentDescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('puf_administration_payments', function (Blueprint $table) {
        $table->string('description')->nullable();
        $table->string('reference');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('puf_administration_payments', function (Blueprint $table) {
          $table->dropColumn('description');
      });
    }
}
