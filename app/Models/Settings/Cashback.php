<?php

namespace App\Models\Settings;

use App\Traits\UnixTimestampsFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed fixed_bonus
 * @property mixed branch_id
 * @property mixed bonus_percentage
 * @property mixed end
 * @property mixed start
 * @property bool is_fixed
 * @property mixed merchant_id
 * @property mixed id
 */
class Cashback extends Model
{
    use HasFactory, SoftDeletes, UnixTimestampsFormat;
    protected $guarded = ['id','created_at','deleted_at','updated_at'];

    protected $hidden = ['deleted_at','created_by','last_updated_by','last_deleted_by','updated_at'];


}
