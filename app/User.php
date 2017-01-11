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

    /**
     * Relationship: has one
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function amazonMws()
    {
        return $this->hasOne('App\AmazonMws');
    }

    /**
     * Relationship: has many
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function amazonRequestHistory()
    {
        return $this->hasMany('App\AmazonRequestHistory');
    }

    /**
     * Relationship: has many
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function amazonMerchantListing()
    {
        return $this->hasMany('App\AmazonMerchantListing');
    }

    protected $nullObjectSubstitutable = [
        'amazonMws'
    ];

    public function __get($key)
    {
        $value = parent::__get($key);

        if (is_null($value)) {
            if (in_array($key, $this->nullObjectSubstitutable)) {
                $value = NullObject::create();
            }
        }

        return $value;
    }

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

    public static function doesEmailExist($email)
    {
        return (bool) self::where('email', $email)->count();
    }

    public static function isEmailVerified($email)
    {
        return (bool) self::where(['email' => $email, 'verified' => 1])->count();
    }
}
