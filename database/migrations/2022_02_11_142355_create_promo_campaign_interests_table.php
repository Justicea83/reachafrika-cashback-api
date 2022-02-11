<?php

use App\Traits\CommonColumns;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoCampaignInterestsTable extends Migration
{
    use CommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_campaign_interests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_campaign_id')->constrained();
            $table->unsignedBigInteger('interest_id');
            $table->unique(['promo_campaign_id','interest_id'],'p_c_unique_all');
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
        Schema::dropIfExists('promo_campaign_interests');
    }
}
