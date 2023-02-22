<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory, SoftDeletes, Uuids;

    protected $dates = ['deleted_at'];

    public $table = 'roles';

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    //Function for role belongs to many users
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_users');
    }

    //Function for role belongs to many permissions
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_roles');
    }

    //function for checking access of roles is true or false
    public function hasRole($modules, $permissions)
    {
        return $this->permissions()->first()->hasPermission($modules, $permissions);
    }
}
