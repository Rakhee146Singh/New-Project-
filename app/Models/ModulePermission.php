<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulePermission extends Model
{
    use Uuids;

    use HasFactory;

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
