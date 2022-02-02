<?php

use App\Traits\CommonColumns;
use App\Utils\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosApprovalsTable extends Migration
{
    use CommonColumns;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_approvals', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->string('status')->default(Status::STATUS_PENDING);
            $table->foreignId('pos_id')->constrained();
            $table->float('amount_due');
            $table->foreignId('payment_mode_id')->constrained();
            $table->string('recipient_name');
            $table->string('recipient_phone');
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
        Schema::dropIfExists('pos_approvals');
    }
}
