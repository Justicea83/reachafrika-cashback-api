<?php

namespace App\Models\Category;

use App\Traits\UnixTimestampsFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Parental\HasParent;

class MerchantCategory extends Category
{
    use HasFactory, HasParent,UnixTimestampsFormat;

    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo($this, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany($this, 'parent_id');
    }
}
