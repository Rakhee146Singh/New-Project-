<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use Uuids;

    use HasFactory;

    public $table = 'modules';

    protected $fillable = [
        'module_code',
        'name',
        'is_active',
        'is_in_menu'
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'module_permissions');
    }
}
