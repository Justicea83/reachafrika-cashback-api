<?php

use App\Traits\CommonColumns;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivePaymentModesTable extends Migration
{
    use CommonColumns;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('active_payment_modes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_mode_id')->constrained();
            $table->foreignId('country_id')->constrained();
            $table->integer('deleted_at')->nullable();
            $table->integer('created_at');
            $table->integer('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('active_payment_modes');
    }
}
