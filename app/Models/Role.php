<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $dates = ['deleted_at'];

    public $table = 'roles';

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * Function for role belongs to many users
     **/
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_users');
    }

    /**
     * Function for role belongs to many permissions
     **/
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_roles');
    }

    /**
     * function for checking access of roles is true or false
     **/
    public function hasRole($modules, $permissions)
    {
        // dd($modules, $permissions);
        foreach ($this->permissions as $permission) {
            // dd($permission);
            return $permission->hasPermission($modules, $permissions);
        }
    }
}
