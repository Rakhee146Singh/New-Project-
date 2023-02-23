<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BaseModel extends Model
{
    use HasFactory;

    /**
     *  function for created_by and updated_by data for users
     *
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });

        static::creating(function ($model) {
            $model->created_by = auth()->user() ? auth()->user()->id : User::where('type', 'superadmin')->first()->id;
        });
        static::updating(function ($model) {
            $model->updated_by = auth()->user() ? auth()->user()->id : User::where('type', 'superadmin')->first()->id;
        });
        static::deleting(function ($model) {
            $model->updated_by = auth()->user() ? auth()->user()->id : User::where('type', 'superadmin')->first()->id;
        });
    }
}
