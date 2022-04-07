<?php

namespace App\Models\Promo\Campaign;

use App\Models\BaseModel;
use App\Models\Promo\Schedule;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Schedule $schedule
 */
class PromoCampaignSchedule extends BaseModel
{
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }


}
