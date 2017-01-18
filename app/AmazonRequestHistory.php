<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AmazonRequestHistory extends Model
{
    protected $fillable = [
        'user_id', 'store_name', 'class', 'type', 'request_id', 'response', 'status'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('status-done', function (Builder $builder) {
            $builder->where('status', '=', '_DONE_');
        });
    }

    /**
     * Relationship: belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function logHistory($data, User $user = null)
    {
        if (! isset($data['user_id'])) {
            $data['user_id'] = $user ? $user->id : Auth::id();
        }

        self::create($data);
    }
}
