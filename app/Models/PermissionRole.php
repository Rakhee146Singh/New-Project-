<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    use Uuids;

    use HasFactory;

    protected $fillable = ([
        'role_id',
        'permission_id'
    ]);
}
