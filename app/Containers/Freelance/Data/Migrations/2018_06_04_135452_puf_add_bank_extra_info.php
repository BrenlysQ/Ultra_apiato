<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PufAddBankExtraInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('puf_freelance_bank_info', function (Blueprint $table) {
            $table->string('bank_name')->nullable();
            $table->string('account_type')->nullable();
          //  $table->string('account_owner');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('puf_freelance_bank_info', function (Blueprint $table) {
            $table->dropColumn('bank_name');
            $table->dropColumn('account_type');
        });
    }
}
