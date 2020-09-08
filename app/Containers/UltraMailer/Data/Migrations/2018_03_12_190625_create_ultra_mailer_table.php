<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUltraMailerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('dbmailer')->create('ultramailer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombrePersonal');
            $table->string('nombreJuridico');
            $table->string('cirif');
            $table->string('telf1');
            $table->string('telf2');
            $table->string('telf3');
            $table->string('mail1');
            $table->string('mail2');
            $table->string('mail3');
            $table->string('ciudad');
            $table->string('orden');
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
        Schema::dropIfExists('ultramailer');
    }
}
