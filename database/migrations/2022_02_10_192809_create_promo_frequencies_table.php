<?php

use App\Traits\CommonColumns;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoFrequenciesTable extends Migration
{
    use CommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_frequencies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('active')->default(true);
            $table->unsignedInteger('count')->comment('view count per user in the interval minutes')->default(1);
            $table->unsignedInteger('interval')->comment('minutes')->nullable();
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
        Schema::dropIfExists('promo_frequencies');
    }
}
