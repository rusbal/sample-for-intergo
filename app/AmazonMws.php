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

    /**
     * Return values in the format required by peron/laravel5-amazon-mws.
     *
     * @return array
     */
    public function storeConfiguration()
    {
        return [
            'merchantId' => $this->merchant_id,
            'marketplaceId' => $this->marketplace_id,
            'keyId' => $this->key_id,
            'secretKey' => $this->secret_key,
        ];
    }
}
