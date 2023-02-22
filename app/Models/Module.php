<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use HasFactory, SoftDeletes, Uuids;

    protected $dates = ['deleted_at'];

    public $table = 'modules';

    protected $fillable = [
        'module_code',
        'name',
        'is_active',
        'is_in_menu'
    ];

    //Function for module belongs to many permissions
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'module_permissions');
    }
}
