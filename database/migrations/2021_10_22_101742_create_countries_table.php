<?php

use App\Traits\CommonColumns;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    use CommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('iso3')->nullable();
            $table->string('iso2')->nullable();
            $table->string('numeric_code')->nullable();
            $table->string('phone_code')->nullable();
            $table->string('capital')->nullable();
            $table->string('currency')->nullable();
            $table->string('tld')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('subregion')->nullable();
            $table->string('region')->nullable();
            $table->string('emoji')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('emojiU')->nullable();
            $table->string('native')->nullable();
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
        Schema::dropIfExists('countries');
    }
}
