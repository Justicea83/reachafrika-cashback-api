<?php

namespace App\Models\Merchant;

use App\Traits\UnixTimestampsFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory,SoftDeletes,UnixTimestampsFormat;

    protected $guarded = ['id','created_at','deleted_at','updated_at'];

    protected $hidden = ['deleted_at','created_by','last_updated_by','last_deleted_by'];

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }
}
