<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHtDollarpriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ht_dollarprice', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('hilbe_price',15,2);
            $table->decimal('dt_price',15,2);
            $table->decimal('percentage',3,2);
            $table->string('timestamp');
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
        Schema::dropIfExists('ht_dollarprice');
    }
}
