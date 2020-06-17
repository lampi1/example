<?php

namespace App\Daran\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\ResetPassword;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use HasRoles;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'email_verified_at'
    ];

    protected $guard_name = 'web';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token,$this->email));
    }

    public function getFullNameAttribute()
    {
        return ucfirst(strtolower($this->name)).' '.ucfirst(strtolower($this->surname));
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


}
