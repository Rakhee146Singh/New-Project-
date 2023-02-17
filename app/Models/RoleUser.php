<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoleUser extends Model
{
    use Uuids;

    use HasFactory;

    protected $fillable = ([
        'role_id',
        'user_id',
        'is_active'
    ]);
}
