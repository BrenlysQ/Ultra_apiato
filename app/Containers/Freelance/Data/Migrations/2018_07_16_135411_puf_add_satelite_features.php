<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PufAddSateliteFeatures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('puf_freelance_features', function (Blueprint $table) {
            $table->integer('satellite_id')->nullable();
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
            $table->dropColumn('satellite_id');
        });
    }
}
