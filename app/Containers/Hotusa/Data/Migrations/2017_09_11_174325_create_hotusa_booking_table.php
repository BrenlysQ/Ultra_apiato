<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotusaBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotusa_booking', function (Blueprint $table) {
            $table->increments('id');
            $table->string('booking_id',8);
            $table->string('short_booking_id',8);
            $table->string('total_amount');
            $table->string('currency');
            $table->string('file_number',12);
            $table->mediumText('pre_book');
            $table->mediumText('confirm_book');
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
        Schema::drop('hotusa_booking');
    }
}
