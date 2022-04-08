<?php

namespace App\Models;

use App\Traits\UnixTimestampsFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
 */
class SettlementBank extends Model
{
    use HasFactory,SoftDeletes,UnixTimestampsFormat;

    protected $guarded = ['id','created_at','deleted_at','updated_at'];
    protected $hidden = ['deleted_at','created_by','last_updated_by','last_deleted_by'];

    public function purposes(): HasMany
    {
        return $this->hasMany(SettlementBankPurpose::class);
    }
}
