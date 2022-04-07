<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TrackOutstandingBalancesOnTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->float('outstanding_balance_before')->default(0)->comment('the outstanding balance of the affected party before the transaction was initiated')->after('balance_after');
            $table->float('outstanding_balance_after')->default(0)->comment('the outstanding balance of the affected party after the transaction was completed')->after('balance_after');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumns('transactions', ['outstanding_balance_before', 'outstanding_balance_after'])) {
                $table->dropColumn('outstanding_balance_after');
                $table->dropColumn('outstanding_balance_before');
            }
        });
    }
}
