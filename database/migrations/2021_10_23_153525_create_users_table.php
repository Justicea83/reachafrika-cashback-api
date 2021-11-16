<?php

use App\Traits\CommonColumns;
use App\Utils\UserUtils;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    use CommonColumns;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->unsignedBigInteger('merchant_id')->nullable();
            $table->string('phone')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('status')->nullable()
                ->default(UserUtils::USER_STATUS_ACTIVE);
            $table->string('avatar')->nullable();
            $table->unsignedBigInteger('suspended_until')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
