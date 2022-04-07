<?php

namespace App\Models\Misc;

use App\Traits\UnixTimestampsFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed $currency_symbol
 * @property mixed $currency
 */
class Country extends Model
{
    use HasFactory, SoftDeletes,UnixTimestampsFormat;
    protected $hidden = ['type','updated_at','created_at','deleted_at','created_by','last_deleted_by','last_updated_by'];

}
