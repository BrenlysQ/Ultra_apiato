<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddpaxresponsibleHotusaBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotusa_booking', function (Blueprint $table) {
          $table->text('pax_responsible');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotusa_booking', function (Blueprint $table) {
            $table->dropColumn('pax_responsible');
        });
    }
}
