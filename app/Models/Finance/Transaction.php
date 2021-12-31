<?php

namespace App\Models\Finance;

use App\Models\BaseModel;
use App\Models\Merchant\Branch;
use App\Models\Merchant\Pos;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $id
 * @property mixed $amount
 * @property mixed $currency_symbol
 * @property mixed $transaction
 * @property mixed $tax_percentage
 * @property mixed $given_discount
 * @property mixed $balance_before
 * @property mixed $balance_after
 * @property mixed $currency
 * @property mixed $status
 * @property mixed $reference
 * @property mixed $created_at
 * @property Branch $branch
 * @property Pos $pos
 * @property User $cashier
 * @property mixed $user_phone
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
}
