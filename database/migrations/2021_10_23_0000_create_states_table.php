<?php

use App\Traits\CommonColumns;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration
{
    use CommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->foreignId('country_id')->nullable()->constrained();
            $table->string('state_code')->nullable();
            $table->string('type')->nullable();
            $table->string('country_code')->nullable();
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
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
        Schema::dropIfExists('states');
    }
}
