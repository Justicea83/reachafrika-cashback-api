<?php

use App\Traits\CommonColumns;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoCampaignSchedulesTable extends Migration
{
    use CommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_campaign_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_campaign_id')->constrained();
            $table->foreignId('schedule_id')->constrained();
            $table->unique(['promo_campaign_id','schedule_id'],'p_c_s_unique_all');
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
        Schema::dropIfExists('promo_campaign_schedules');
    }
}
