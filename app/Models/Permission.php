<?php

namespace App\Models;

use App\Traits\UnixTimestampsFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * @property mixed $parent_id
 * @property mixed $id
 */
class Permission extends SpatiePermission
{
    use HasFactory,SoftDeletes,UnixTimestampsFormat;

    protected $appends = ['sub_permissions'];
    public function getSubPermissionsAttribute(){
        if(!$this->parent_id){
            return self::query()->where('parent_id',$this->id)->get();
        }
        return null;
    }

    public static function generateFor($name,$parent = null,$description = null) :Model
    {
        $permission = [
            'name' => strtolower($name),
            'display_name'=>Str::title(str_replace('_',' ',$name)),
            'guard_name' => 'web'
        ];
        if($parent){
            $permission['parent_id'] = $parent;
        }
        if($description){
            $permission['description'] = $description;
        }
        return self::query()->firstOrCreate(
            $permission
        );
    }

    protected $hidden = [
        'deleted_at',
        'created_by',
        'last_updated_by',
        'last_deleted_by',
        'updated_at',
        'pivot'
    ];
}
