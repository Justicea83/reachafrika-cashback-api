<?php

use App\Traits\CommonColumns;
use App\Utils\CashbackUtils;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashbacksTable extends Migration
{
    use CommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashbacks', function (Blueprint $table) {
            $table->id();
            $table->decimal('start')->nullable();
            $table->decimal('end')->nullable();
            $table->decimal('bonus_percentage')->nullable();
            $table->boolean('is_fixed');
            $table->decimal('fixed_bonus')->nullable();
            $table->foreignId('merchant_id')->constrained();
            $table->foreignId('branch_id')->constrained();
            $table->string('status')->default(CashbackUtils::CASHBACK_STATUS_ACTIVE);
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
        Schema::dropIfExists('cashbacks');
    }
}
