<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
Use Carbon\Carbon;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    protected $guard_name = 'api';

    use HasApiTokens,Notifiable,HasRoles;

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function profiles()
    {
        return $this->hasOne(Profile::class);
    }
    public function demands()
    {
        return $this->hasMany(Demand::class);
    }

    public function mobileTokens()
    {
        return $this->hasMany(MobileToken::class);
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone','mobile_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
