<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory, Uuids;

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public $table = 'roles';

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_users');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_roles');
    }
}
