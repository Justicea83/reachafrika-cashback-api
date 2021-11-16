<?php

use App\Traits\CommonColumns;
use App\Utils\MerchantUtils;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosTable extends Migration
{
    use CommonColumns;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('branch_id')->constrained();
            $table->foreignId('merchant_id')->constrained();
            $table->string('code');
            $table->string('status')->nullable()
                ->default(MerchantUtils::MERCHANT_STATUS_ACTIVE);
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
        Schema::dropIfExists('pos');
    }
}
