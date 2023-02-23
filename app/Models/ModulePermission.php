<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModulePermission extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $dates = ['deleted_at'];

    public $table = 'module_permissions';

    protected $fillable = ([
        'permission_id',
        'module_id',
        'add_access',
        'edit_access',
        'delete_access',
        'view_access',
        'created_by',
        'updated_by',
        'deleted_by'
    ]);
}
