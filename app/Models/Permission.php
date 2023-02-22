<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory, SoftDeletes, Uuids;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    //Function for permission has many modules
    public function modules()
    {
        return $this->hasMany(ModulePermission::class, 'permission_id');
    }

    //Function for permission belongs to many roles
    public function roles()
    {
        return  $this->belongsToMany(Role::class, 'permission_role');
    }

    //function for checking access of permissions is true or false
    public function hasPermission($modules, $permissions)
    {
        $module = Module::where('module_code', $modules)->first();
        $check = $this->modules()->where('module_id', $module->id)
            ->where($permissions, true)
            ->first();
        if ($check) {
            return true;
        } else {
            return false;
        }
    }
}
