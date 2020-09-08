<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PufAddPlusRanking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('puf_freelance_features', function (Blueprint $table) {
        $table->float('ranking_plus');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('puf_freelance_features', function (Blueprint $table) {
          $table->dropColumn('ranking_plus');
      });
    }
}
