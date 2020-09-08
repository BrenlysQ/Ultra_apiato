<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PufFreelanceStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('puf_freelance_campaigns', function (Blueprint $table) {
          $table->increments('id');
          $table->dateTime('init_plan');
          $table->dateTime('end_plan');
          $table->integer('status');
          $table->integer('created_by');
          $table->integer('id_freelance');
          $table->mediumText('data');
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
        Schema::drop('puf_freelance_campaigns');
    }
}
