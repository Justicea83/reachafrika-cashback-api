<?php

use App\Traits\CommonColumns;
use App\Utils\MerchantUtils;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    use CommonColumns;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('opens_at')->nullable();
            $table->time('closes_at')->nullable();
            $table->longText('location')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('merchant_id');
            $table->float('lat')->nullable();
            $table->string('code')->index();
            $table->float('lng')->nullable();
            $table->string('status')->nullable()
                ->default(MerchantUtils::MERCHANT_STATUS_ACTIVE);

            $this->useCommonColumns($table);

            $table->foreign('merchant_id')->references('id')->on('merchants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
}
