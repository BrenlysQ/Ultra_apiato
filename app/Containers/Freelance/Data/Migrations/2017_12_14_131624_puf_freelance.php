<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PufFreelance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('puf_freelance', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name');
          $table->string('lastname');
          $table->string('email')->unique();
          $table->string('phone');
          $table->string('city');
          $table->string('country');
          $table->string('latitude');
          $table->string('longitude');
          $table->string('image');
          $table->string('facebook')->nullable();
          $table->string('twitter')->nullable();
          $table->string('instagram')->nullable();
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
        Schema::drop('puf_freelance');
    }
}
