<?php

namespace App\Models;

use App\Traits\UnixTimestampsFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasFactory,SoftDeletes,UnixTimestampsFormat;

    protected $hidden = [
        'deleted_at',
        'created_by',
        'last_updated_by',
        'last_deleted_by',
        'updated_at',
        'pivot'
    ];
}
