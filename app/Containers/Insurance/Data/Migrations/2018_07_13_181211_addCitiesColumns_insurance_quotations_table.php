<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCitiesColumnsInsuranceQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insurance_quotations', function (Blueprint $table) {
            $table->string('destination_city');
            $table->string('departure_city');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insurance_quotations', function (Blueprint $table) {
            $table->dropColumn(['destination_city', 'departure_city']);
        });
    }
}
