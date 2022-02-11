<?php

use App\Traits\CommonColumns;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoCampaignProfessionsTable extends Migration
{
    use CommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_campaign_professions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_campaign_id')->constrained();
            $table->unsignedBigInteger('profession_id');
            $table->unique(['promo_campaign_id','profession_id'],'p_c_p_unique_all');
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
        Schema::dropIfExists('promo_campaign_professions');
    }
}
