<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPgtdataPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pup_payments', function (Blueprint $table) {
          $table->nullableMorphs('pgatewaydata');
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
            //$table->dropColumn('customer');
            $table->dropColumn('pgatewaydata_type');
            $table->dropColumn('pgatewaydata_id')->unsigned();
        });
    }
}
