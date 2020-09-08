<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PufAddPlanRanking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('puf_freelance_campaigns', function (Blueprint $table) {
        $table->float('ranking');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('puf_freelance_campaigns', function (Blueprint $table) {
          $table->dropColumn('ranking');
      });
    }
}
