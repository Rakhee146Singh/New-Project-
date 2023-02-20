<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use Uuids;

    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];


    public function modules()
    {
        return $this->hasMany(ModulePermission::class, 'permission_id');
    }

    public function roles()
    {
        return  $this->belongsToMany(Role::class, 'permission_role');
    }
}
