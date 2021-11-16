<?php

use App\Traits\CommonColumns;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    use CommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->foreignId('country_id')->nullable()->constrained();
            $table->foreignId('state_id')->nullable()->constrained();
            $table->string('state_code')->nullable();
            $table->string('country_code')->nullable();
            $table->string('wikiDataId')->nullable();
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
        Schema::dropIfExists('cities');
    }
}
