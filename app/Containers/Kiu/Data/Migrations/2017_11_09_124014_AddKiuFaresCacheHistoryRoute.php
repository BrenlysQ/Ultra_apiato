<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKiuFaresCacheHistoryRoute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kiu_fares_cache_history', function (Blueprint $table) {
          $table->text('route');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kiu_fares_cache_history', function (Blueprint $table) {
            $table->dropColumn('route');
        });
    }
}
