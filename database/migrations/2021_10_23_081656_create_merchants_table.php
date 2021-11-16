<?php

use App\Traits\CommonColumns;
use App\Utils\MerchantUtils;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantsTable extends Migration
{
    use CommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('primary_email')->unique();
            $table->string('primary_phone')->unique()->nullable();
            $table->unsignedBigInteger('main_branch_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('code')->index();
            $table->timestamp('primary_email_verified_at')->nullable();
            $table->timestamp('primary_phone_verified_at')->nullable();
            $table->longText('location')->nullable();
            $table->longText('about')->nullable();
            $table->string('website')->nullable();
            $table->string('status')->nullable()
                ->default(MerchantUtils::MERCHANT_STATUS_PENDING);
            $table->string('avatar')->nullable();
            $table->foreignId("head_office_country_id")->nullable()->constrained("countries");
            $table->foreignId("head_office_state_id")->nullable()->constrained("states");
            $table->string("head_office_city")->nullable();
            $table->string("head_office_street")->nullable();
            $table->longText("head_office_address")->nullable();
            $table->longText("head_office_building")->nullable();
            $table->json("extra_data")->nullable();

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
        Schema::dropIfExists('merchants');
    }
}
