<?php

namespace App\Models\Merchant;

use App\Models\Category\MerchantCategory;
use App\Models\User;
use App\Traits\UnixTimestampsFormat;
use ArrayAccess;
use Database\Factories\MerchantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed id
 * @property mixed|string status
 * @property mixed extra_data
 * @property array|ArrayAccess|mixed head_office_country_id
 * @property array|ArrayAccess|mixed head_office_state_id
 * @property array|ArrayAccess|mixed head_office_city
 * @property array|ArrayAccess|mixed head_office_street
 * @property array|ArrayAccess|mixed head_office_building
 * @property array|ArrayAccess|mixed head_office_address
 * @property array|ArrayAccess|mixed name
 * @property array|ArrayAccess|mixed category_id
 * @property array|ArrayAccess|mixed website
 * @property array|ArrayAccess|mixed about
 * @property false|mixed|string avatar
 */
class Merchant extends Model
{
    use HasFactory,SoftDeletes,UnixTimestampsFormat;

    protected $guarded = ['id','created_at','deleted_at','updated_at'];
    protected $hidden = ['deleted_at','created_by','last_updated_by','last_deleted_by'];

    protected $casts = [
        'extra_data' => 'array'
    ];

    public function mainBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class,'main_branch_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MerchantCategory::class,'category_id');
    }

    protected static function newFactory(): MerchantFactory
    {
        return MerchantFactory::new();
    }
}
