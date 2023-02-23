<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $dates = ['deleted_at'];

    public $table = 'modules';

    protected $fillable = [
        'module_code',
        'name',
        'is_active',
        'is_in_menu',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * Function for module belongs to many permissions
     *
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'module_permissions');
    }
}
