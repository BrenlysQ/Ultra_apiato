<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrenciesSeparatorsFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('api_currencies', function (Blueprint $table) {
          $table->char('separator',1);
          $table->char('decimal_separator',1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('api_currencies', function (Blueprint $table) {
            $table->dropColumn(['separator', 'decimal_separator']);
        });
    }
}
