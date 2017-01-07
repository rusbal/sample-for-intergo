<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AmazonMws extends Model
{
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
