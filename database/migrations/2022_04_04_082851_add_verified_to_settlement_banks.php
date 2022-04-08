<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVerifiedToSettlementBanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settlement_banks', function (Blueprint $table) {
            $table->boolean('verified')->after('merchant_id')->default(false);
            $table->unique(['account_no','account_name','bank_name'], 'all_unique_constraint');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settlement_banks', function (Blueprint $table) {
            $table->dropColumn('verified');
            $table->dropIndex('all_unique_constraint');
        });
    }
}
