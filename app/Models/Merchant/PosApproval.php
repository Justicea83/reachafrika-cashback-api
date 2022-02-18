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
 * @property mixed $pos_id
 * @property mixed $status
 * @property mixed $reference
 */
class PosApproval extends BaseModel
{
    public function paymentMode(): BelongsTo
    {
        return $this->belongsTo(PaymentMode::class);
    }
}
