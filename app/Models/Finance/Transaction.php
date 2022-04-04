<?php

namespace App\Models\Finance;

use App\Models\BaseModel;
use App\Models\Merchant\Branch;
use App\Models\Merchant\Pos;
use App\Models\User;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed|string $transaction
 * @property float|mixed $given_discount
 * @property mixed $balance_before
 * @property mixed|string $group_reference
 * @property mixed|string $status
 * @property mixed $user_phone
 * @property mixed|string $platform
 * @property mixed $merchant_id
 * @property mixed $branch_id
 * @property mixed $pos_id
 * @property mixed|string $reference
 * @property mixed|string $transaction_type
 * @property mixed $amount
 * @property mixed $currency
 * @property mixed $currency_symbol
 * @property mixed $created_by
 * @property float|mixed $balance_after
 * @property int|mixed $payment_mode_id
 * @property mixed $account
 * @property mixed $outstanding_balance_before
 * @property mixed $outstanding_balance_after
 * @property mixed $tax_percentage
 * @property PaymentMode $paymentMode
 * @property Branch $branch
 * @property User $cashier
 * @property Pos $pos
 * @property mixed $service_charge
 */
class Transaction extends BaseModel
{
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function pos(): BelongsTo
    {
        return $this->belongsTo(Pos::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class,'created_by');
    }

    protected static function newFactory(): TransactionFactory
    {
        return TransactionFactory::new();
    }

    public function paymentMode(): BelongsTo
    {
        return $this->belongsTo(PaymentMode::class);
    }
}
