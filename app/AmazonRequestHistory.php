<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class AmazonRequestHistory extends Model
{
    protected $fillable = [
        'user_id', 'store_name', 'class', 'type', 'request_id', 'response'
    ];

    /**
     * Relationship: belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function logHistory($data, $user = null)
    {
        if (! isset($data['user_id'])) {
            $data['user_id'] = $user ? $user->id : Auth::id();
        }

        self::create($data);
    }
}
