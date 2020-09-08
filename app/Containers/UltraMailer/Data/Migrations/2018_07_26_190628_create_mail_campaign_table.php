<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('dbmailer')->create('mail_campaign', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_campaign');
            $table->longText('receptores');
            $table->string('message');
            $table->integer('header_mail');
            $table->string('subject');
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
        Schema::dropIfExists('mail_campaign');
    }
}
