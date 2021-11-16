<?php

namespace App\Models;

use App\Traits\UnixTimestampsFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as SpatieRole;
class Role extends SpatieRole
{
    use HasFactory,SoftDeletes,UnixTimestampsFormat;

    protected $guarded = ['id', 'created_at', 'deleted_at', 'updated_at'];

    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
        'created_by',
        'last_updated_by',
        'last_deleted_by',
        'updated_at',
        'pivot'
    ];

}
