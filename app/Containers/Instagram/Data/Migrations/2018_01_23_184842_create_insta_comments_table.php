<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstaCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insta_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('comment_id');
            $table->string('user_id');
            $table->string('user');
            $table->string('timestamp_comment');
            $table->integer('media_id');
            $table->mediumText('comment');
            $table->integer('status_id');
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
        Schema::drop('insta_comments');
    }
}
