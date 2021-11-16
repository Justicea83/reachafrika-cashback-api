<?php

namespace App\Models\Category;

use App\Traits\UnixTimestampsFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Parental\HasChildren;

/**
 * @property mixed id
 * @property mixed name
 * @property mixed parent_id
 * @property Category parent
 * @property Collection children
 */
class Category extends Model
{
    use HasFactory,SoftDeletes,HasChildren,UnixTimestampsFormat;

    protected $guarded = ['id','created_at','deleted_at','updated_at'];

    protected $hidden = ['type','updated_at','created_at','deleted_at','created_by','last_deleted_by','last_updated_by'];
}
