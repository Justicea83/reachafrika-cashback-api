<?php

namespace App\Models;

use App\Models\Finance\PaymentMode;
use App\Traits\UnixTimestampsFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property mixed account_name
 * @property mixed account_no
 * @property mixed bank_name
 * @property bool $verified
 * @property mixed $id
 * @property Collection $purposes
 * @property array|\ArrayAccess|mixed $payment_mode_id
 * @property mixed $extra_info
 */
class SettlementBank extends Model
{
    use HasFactory, SoftDeletes, UnixTimestampsFormat;

    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];
    protected $hidden = ['deleted_at', 'created_by', 'last_updated_by', 'last_deleted_by'];

    protected $casts = [
        'verified' => 'boolean',
        'extra_info' => 'array'
    ];

    public function purposes(): HasMany
    {
        return $this->hasMany(SettlementBankPurpose::class);
    }

    public function paymentMode(): BelongsTo
    {
        return $this->belongsTo(PaymentMode::class);
    }
}
