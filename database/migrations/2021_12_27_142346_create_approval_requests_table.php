<?php

use App\Traits\CommonColumns;
use App\Utils\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalRequestsTable extends Migration
{
    use CommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default(Status::STATUS_PENDING);
            $table->foreignId('pos_id')->constrained();
            $table->string('phone');
            $table->string('currency');
            $table->string('currency_symbol');
            $table->float('amount');
            $table->json('extra_info')->nullable();
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
        Schema::dropIfExists('approval_requests');
    }
}
