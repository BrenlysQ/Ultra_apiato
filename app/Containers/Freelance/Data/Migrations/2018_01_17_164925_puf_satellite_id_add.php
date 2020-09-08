<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PufSatelliteIdAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('puf_freelance', function (Blueprint $table) {
        $table->integer('id_satellite');
        $table->string('gender');
        $table->string('website')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('puf_freelance', function (Blueprint $table) {
          $table->dropColumn('id_satellite');
          $table->string('gender');
          $table->string('website')->nullable();
      });
    }
}
