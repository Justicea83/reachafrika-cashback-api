<?php

namespace App\Models;

use App\Models\Merchant\Merchant;
use App\Models\Merchant\Pos;
use App\Traits\UnixTimestampsFormat;
use Illuminate\Auth\Passwords\CanResetPassword as ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property mixed first_name
 * @property mixed last_name
 * @property mixed email
 * @property mixed phone
 * @property mixed status
 * @property mixed email_verified_at
 * @property mixed phone_verified_at
 * @property mixed created_at
 * @property mixed id
 * @property mixed merchant_id
 * @property float|int|mixed|string suspended_until
 * @property mixed password
 * @property Pos $pos
 * @property Merchant $merchant
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, ResetPassword, SoftDeletes,UnixTimestampsFormat;

    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];

    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
        'created_by'
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    public function pos(): HasOne
    {
        return $this->hasOne(Pos::class);
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

}
