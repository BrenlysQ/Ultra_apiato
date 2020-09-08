<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVoucherColumnsInsuranceQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insurance_quotations', function (Blueprint $table) {
            $table->integer('voucher_id');
            $table->string('document_url');
            $table->string('voucher_status');
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
            $table->dropColumn(['voucher_id', 'document_url', 'voucher_status']);
        });
    }
}
