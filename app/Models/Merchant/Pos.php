<?php

namespace App\Models\Merchant;

use App\Models\User;
use App\Traits\UnixTimestampsFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed|string status
 * @property mixed id
 */
class Pos extends Model
{
    use HasFactory,UnixTimestampsFormat,SoftDeletes;

    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];

    protected $hidden = [
        'deleted_at',
        'created_by'
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
