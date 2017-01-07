<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AmazonMws extends Model
{
    protected $fillable = [
        'merchant_id', 'marketplace_id', 'key_id', 'secret_key'
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
}
