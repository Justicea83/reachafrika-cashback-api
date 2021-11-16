<?php

use App\Traits\CommonColumns;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettlementBanksTable extends Migration
{
    use CommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settlement_banks', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_no')->nullable();
            $table->foreignId('merchant_id')->constrained();
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
        Schema::dropIfExists('settlement_banks');
    }
}
