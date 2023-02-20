<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoleUser extends Model
{
    use Uuids;

    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ([
        'role_id',
        'user_id',
        'is_active'
    ]);
}
