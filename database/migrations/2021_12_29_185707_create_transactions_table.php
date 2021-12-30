<?php

use App\Traits\CommonColumns;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    use CommonColumns;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->float('amount')->comment('the actual amount of the transaction');
            $table->string('transaction')
                ->comment('this indicates whether a transaction is a credit or debit'); //debit or credit
            $table->string('transaction_type')
                ->comment('this indicates the actual cause of the transaction like eg. Cashback Transaction, bonus transaction'); //debit or credit
            $table->float('tax_percentage')->default(0)->comment('any tax rate');
            $table->float('given_discount')->default(0)->comment('the discount allowed on the transaction');
            $table->float('balance_before')->nullable()->comment('the balance of the affected party before the transaction was initiated');
            $table->float('balance_after')->nullable()->comment('the balance of the affected party after the transaction was completed');
            $table->string('currency')->comment('the currency of the transaction');
            $table->string('currency_symbol')->comment('the symbol of the currency');
            $table->string('status')->default('pending')->comment('this indicates the status of the transaction');
            $table->string('reference')->comment('the extra reference given to a transaction');
            $table->string('group_reference')->comment('the id given to a group of similar transactions');
            $table->float('service_charge')->default(0)->comment('the charges on the transaction');
            $table->string('user_phone')->nullable()->comment('the user who effect this transactions phone');
            $table->string('platform')->nullable()->comment('the app/platform from which this transaction occurred');
            $table->string('payment_mode')->default('reachafrika_core_app')->comment('from which service was the payment made');
            $table->foreignId('pos_id')->nullable()->constrained();
            $table->foreignId('branch_id')->nullable()->constrained();
            $table->foreignId('merchant_id')->constrained();
            $table->text('description')->comment('any comments on the transaction')->nullable();
            $table->json('extra_info')->comment('any extra data in the future')->nullable();
            $this->useCommonColumns($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
