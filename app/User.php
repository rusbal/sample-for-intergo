<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function($user){
            $user->token = str_random(30);
        });
    }

    public static function confirmEmail($token)
    {
        $user = self::where('token', $token)->first();

        if ($user) {
            $user->token = null;
            $user->verified = true;
            $user->save();
        }

        return $user;
    }

    public static function isVerifiedEmail($email)
    {
        return (bool) self::where(['email' => $email, 'verified' => 1])->count();
    }
}
