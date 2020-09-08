<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPercentageBsfColumnToHtConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ht_config', function (Blueprint $table) {
            $table->decimal('percentage_bsf', 3,2)->after('percentage_notice');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ht_config', function (Blueprint $table) {
            $table->dropColumn('percentage_bsf');
        });
    }
}
