<?php

use App\Traits\CommonColumns;
use App\Utils\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoCampaignsTable extends Migration
{
    use CommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_campaigns', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('start');
            $table->unsignedInteger('end');
            $table->string('type')->comment('media type of the campaign');
            $table->string('title');
            $table->foreignId('merchant_id')->constrained();
            $table->double('budget');
            $table->unsignedInteger('impressions');
            $table->unsignedInteger('impressions_track');
            $table->text('media');
            $table->text('thumbnail');
            $table->boolean('visible')->default(false);
            $table->longText('description')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->unsignedInteger('approved_at')->nullable();
            $table->longText('callback_url')->nullable();
            $table->longText('message')->nullable();
            $table->string('gender')->default('all');
            $table->string('marital_status')->default('all');
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            $table->boolean('blocked')->default(false);
            $table->integer('duration')->default(0);
            $table->boolean('locked')->comment('use this field to remove the campaign from the stream temporary')->default(false);
            $table->unsignedBigInteger('delete_requested_at')->comment('this field will be used to remove the campaign from the stream after it has been locked')->nullable();
            $table->foreignId('promo_frequency_id')->constrained();
            $table->string('status')->default(Status::STATUS_PENDING);


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
        Schema::dropIfExists('promo_campaigns');
    }
}
