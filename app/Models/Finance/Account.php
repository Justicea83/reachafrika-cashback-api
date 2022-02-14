<?php

namespace App\Models\Finance;

use App\Models\BaseModel;
use App\Models\Merchant\Merchant;
use Database\Factories\AccountFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $balance
 * @property mixed $type
 */
class Account extends BaseModel
{
    protected static function newFactory(): AccountFactory
    {
        return AccountFactory::new();
    }
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }
}
