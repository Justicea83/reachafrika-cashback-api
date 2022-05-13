<?php

namespace App\Models\Settlements;

use App\Traits\UnixTimestampsFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $settlement_bank_id
 * @property SettlementBank $settlementBank
 */
class SettlementBankPurpose extends Model
{
    use HasFactory,UnixTimestampsFormat;

    protected $guarded = ['id','created_at','deleted_at','updated_at'];
    protected $hidden = ['deleted_at','created_by','last_updated_by','last_deleted_by'];

    public function settlementBank(): BelongsTo
    {
        return $this->belongsTo(SettlementBank::class);
    }
}
