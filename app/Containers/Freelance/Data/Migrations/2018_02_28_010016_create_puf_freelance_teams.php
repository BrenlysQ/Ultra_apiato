<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePufFreelanceTeams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('puf_freelance_teams', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name');
          $table->integer('partners_limit');
          $table->integer('satellite_owner');
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
        Schema::drop('puf_freelance_teams');
    }
}
