<?php

namespace App\Models\Merchant;

use App\Models\BaseModel;
use App\Models\Finance\PaymentMode;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $amount_due
 * @property mixed $recipient_name
 * @property mixed $recipient_phone
 * @property PaymentMode $paymentMode
 */
class PosApproval extends BaseModel
{
    public function paymentMode(): BelongsTo
    {
        return $this->belongsTo(PaymentMode::class);
    }
}
