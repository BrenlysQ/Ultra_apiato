<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PufFreelanceFeatures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('puf_freelance_features', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('id_freelance');
          $table->integer('exp_years');
          $table->integer('completed_sales');
          $table->mediumText('languages');
          $table->mediumText('skills');
          $table->float('ranking');
          $table->timestamps();
          $table->softDeletes();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('puf_freelance_features');
    }
}
