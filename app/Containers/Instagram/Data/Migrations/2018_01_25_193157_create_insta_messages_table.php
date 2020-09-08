<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstaMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insta_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('item_id')->nullable();
            $table->string('userid_interaction');
            $table->string('from_user');
            $table->string('item_type');
            $table->string('timestamp_message');
            $table->text('threads_id')->nullable();
            $table->string('like',2)->nullable();
            $table->mediumText('text_message')->nullable();
            $table->integer('comment_id')->nullable();
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
        Schema::drop('insta_messages');
    }
}
