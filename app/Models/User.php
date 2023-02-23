<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    use SoftDeletes;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'is_active',
        'is_first_login',
        'code',
        'type',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * Function for user belongs to many roles
     **/
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }

    /**
     * function for checking access of users is true or false
     **/
    public function hasAccess($modules, $permissions)
    {
        // dd($modules, $permissions);
        foreach ($this->roles as $role) {
            // dd($role);
            return $role->hasRole($modules, $permissions);
        }
    }

    /**
     * function for creating or updating users data
     **/
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
