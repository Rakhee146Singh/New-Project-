<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermissionRole extends Model
{
    use HasFactory, SoftDeletes, Uuids;
    protected $dates = ['deleted_at'];

    protected $fillable = ([
        'role_id',
        'permission_id'
    ]);
}
