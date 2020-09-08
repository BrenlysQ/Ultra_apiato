<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPercentageBsfColumnToHtDollarpriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ht_dollarprice', function (Blueprint $table) {
            $table->decimal('ht_config_percentage_bsf')->after('percentage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ht_dollarprice', function (Blueprint $table) {
            $table->dropColumn('percentage_bsf');
        });
    }
}
