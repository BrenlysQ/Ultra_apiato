<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcodeApiCurrenciesVisibleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('api_currencies', function (Blueprint $table) {
          $table->string('code_visible', 8);
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
            $table->dropColumn('code_visible');
        });
    }
}
