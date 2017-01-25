<?php

namespace App;

use Four13\Plans\Plan;
use Laravel\Cashier\Billable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Billable;
    use Notifiable;

    protected $dates = [
        'created_at',
        'updated_at',
        'trial_ends_at',
    ];

    const DEFAULT_PLAN = 'free';

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

    /**
     * @param $email
     * @return bool
     */
    public static function isEmailVerified($email)
    {
        return (bool) self::where(['email' => $email, 'verified' => 1])->count();
    }

    /**
     * Checkes if user is subscribed using Laravel Cashier
     *
     * @param string $subscription
     * @return bool
     */
    public function isSubscribed($subscription = 'main')
    {
        return $this->subscribed($subscription);
    }

    /**
     * Returns main subscription
     *
     * @param string $subscription
     * @return \Laravel\Cashier\Subscription|null
     */
    public function getSubscription($subscription = 'main')
    {
        return $this->subscription($subscription);
    }

    public function cancelSubscription($subscription = 'main', $immediate = false)
    {
        if ($immediate) {
            return $this->subscription($subscription)->cancelNow();
        }

        return $this->subscription($subscription)->cancel();
    }

    public function currentPlan()
    {
        if ($this->isSubscribed()) {
            return $this->getSubscription()->stripe_plan;
        } else {
            return self::DEFAULT_PLAN;
        }
    }

    public function isPlanAllocationUsedUp()
    {
        $plan = $this->currentPlan();
        $used = $this->monitoredListingCount();

        return Plan::isAllocationUsedUp($plan, $used);
    }

    public function planStats()
    {
        $plan = $this->currentPlan();
        $used = $this->monitoredListingCount();
        $isUsedUp = Plan::isAllocationUsedUp($plan, $used);

        return (object) [
            'plan' => $plan,
            'monitorCount' => $used,
            'isUsedUp' => $isUsedUp,
        ];
    }

    public function monitoredListingCount()
    {
        return $this->amazonMerchantListing->where('will_monitor', 1)->count();
    }
}
