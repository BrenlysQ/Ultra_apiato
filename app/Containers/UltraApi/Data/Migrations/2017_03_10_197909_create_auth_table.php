<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         //
         Schema::create('external_auths', function (Blueprint $table) {
             $table->string('type', 10)->unique();
             $table->string('title');
             $table->text('auth');
             $table->timestamp('expirein')->default(null);
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
         Schema::drop('external_auths');
     }
}
