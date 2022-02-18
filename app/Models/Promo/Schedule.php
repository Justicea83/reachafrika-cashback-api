<?php

namespace App\Models\Promo;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property PromoDay $day
 * @property PromoTime $fromTime
 * @property PromoTime $toTime
 * @property bool|mixed $active
 */
class Schedule extends BaseModel
{
    public function toTime(): BelongsTo
    {
        return $this->belongsTo(PromoTime::class, 'to');
    }

    public function fromTime(): BelongsTo
    {
        return $this->belongsTo(PromoTime::class, 'from');
    }

    public function day(): BelongsTo
    {
        return $this->belongsTo(PromoDay::class, 'promo_day_id');
    }
}
