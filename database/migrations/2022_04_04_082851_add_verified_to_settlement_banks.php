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
            $table->json('extra_info')->after('merchant_id')->nullable();
            $table->foreignId('payment_mode_id')->after('merchant_id')->nullable()->constrained();
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
            $table->dropForeign(['payment_mode_id']);
            $table->dropColumn('payment_mode_id');
            $table->dropIndex('settlement_banks_payment_mode_id_foreign');
            $table->dropIndex('all_unique_constraint');
        });
    }
}
