<?php

namespace App\Models\Promo\Campaign;

use App\Models\BaseModel;
use App\Models\Merchant\Merchant;
use App\Models\Promo\PromoFrequency;
use Database\Factories\PromoCampaignFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Collection\Collection;

/**
 * @property mixed $merchant_id
 * @property false|mixed|string $media
 * @property mixed|string $thumbnail
 * @property int|mixed $start
 * @property int|mixed $end
 * @property mixed $type
 * @property mixed $duration
 * @property mixed $title
 * @property mixed $budget
 * @property mixed $description
 * @property mixed $marital_status
 * @property mixed $gender
 * @property mixed $message
 * @property mixed $callback_url
 * @property mixed $promo_frequency_id
 * @property mixed $max_age
 * @property mixed $min_age
 * @property mixed $lng
 * @property mixed $lat
 * @property float|int|mixed $impressions
 * @property float|int|mixed $impressions_track
 * @property mixed $status
 * @property mixed $delete_requested_at
 * @property mixed $blocked
 * @property PromoFrequency $frequency
 * @property Collection $schedules
 * @property mixed $currency
 * @property false|mixed $last_scheduled_at
 * @property Merchant $merchant
 */
class PromoCampaign extends BaseModel
{
    public function interests(): HasMany
    {
        return $this->hasMany(PromoCampaignInterest::class);
    }

    public function professions(): HasMany
    {
        return $this->hasMany(PromoCampaignProfession::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(PromoCampaignSchedule::class);
    }

    public function frequency(): BelongsTo
    {
        return $this->belongsTo(PromoFrequency::class, 'promo_frequency_id');
    }


    protected static function newFactory(): PromoCampaignFactory
    {
        return PromoCampaignFactory::new();
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);

    }
}
