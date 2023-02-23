<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /*
        *Function for permission has many modules
    */
    public function modules()
    {
        return $this->hasMany(ModulePermission::class, 'permission_id');
    }


    /*
        * Function for permission belongs to many roles
    */
    public function roles()
    {
        return  $this->belongsToMany(Role::class, 'permission_role');
    }

    /*
        * function for checking access of permissions is true or false
    */
    public function hasPermission($modules, $permissions)
    {
        // dd($modules, $permissions);
        $module = Module::where('module_code', $modules)->first();
        // dd($permissions);
        $check = $this->modules()->where('module_id', $module->id)
            ->where($permissions, true)
            ->first();
        // dd($check);
        if ($check) {
            return true;
        } else {
            return false;
        }
    }
}
