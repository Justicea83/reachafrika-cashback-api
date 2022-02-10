<?php

use App\Traits\CommonColumns;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFcmDeviceGroupsTable extends Migration
{
    use CommonColumns;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fcm_device_groups', function (Blueprint $table) {
            $table->id();
            $table->string('notification_key_name')->unique()->comment('the unique username that going to be used forever');
            $table->string('notification_key')->comment('the unique token for the user to send messages to');
            $table->foreignId('user_id')->unique()->comment('user associated with the token')->constrained();
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
        Schema::dropIfExists('fcm_device_groups');
    }
}
