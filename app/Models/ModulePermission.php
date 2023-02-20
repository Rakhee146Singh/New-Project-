<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModulePermission extends Model
{
    use Uuids;

    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    public $table = 'module_permissions';

    protected $fillable = ([
        'permission_id',
        'module_id',
        'add_access',
        'edit_access',
        'delete_access',
        'view_access'
    ]);
}
