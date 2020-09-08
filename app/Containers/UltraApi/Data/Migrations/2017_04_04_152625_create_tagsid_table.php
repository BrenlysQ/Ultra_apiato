<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         //
         Schema::create('gds_tags_id', function (Blueprint $table) {
             $table->smallInteger('type')->default(1);
             $table->string('tag_id');
             $table->string('tag_pu');
             $table->dateTime('gen_date');
             $table->timestamps();
         });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         //
         Schema::drop('gds_tags_id');
     }
}
