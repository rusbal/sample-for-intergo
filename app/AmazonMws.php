<?php

namespace App;

use Four13\AmazonMws\RequestReport;
use Peron\AmazonMws\AmazonReport;
use Illuminate\Database\Eloquent\Model;
use Peron\AmazonMws\AmazonInventoryList;
use Peron\AmazonMws\AmazonReportRequest;

class AmazonMws extends Model
{
    use ObjectTrait;

    protected $fillable = [
        'merchant_id', 'marketplace_id', 'key_id', 'secret_key', 'mws_auth_token'
    ];

    public static $rules = [
        'merchant_id' => 'required|size:14',
        'mws_auth_token' => 'required',
    ];

    const DEFAULT_KEY_ID = 'AKIAITF5AZ2VRC4WXBGA';
    const DEFAULT_SECRET_KEY = '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';
    const UNITED_STATES_MARKETPLACE_ID = 'ATVPDKIKX0DER';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->key_id = self::DEFAULT_KEY_ID;
            $model->secret_key = self::DEFAULT_SECRET_KEY;
        });
    }

    /**
     * Important: Mutators are only executed if there is assignment to the attribute.
     * Mutators used to assign default values
     */
    public function setMwsAuthTokenAttribute($val)
    {
        $this->attributes['mws_auth_token'] = trim($val);
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

    /**
     * Return values in the format required by peron/laravel5-amazon-mws.
     *
     * @return array
     */
    public function storeConfiguration()
    {
        $storeConfig = [
            'merchantId' => $this->merchant_id,
            'marketplaceId' => $this->marketplace_id,
            'keyId' => $this->key_id,
            'secretKey' => $this->secret_key,
        ];

        return [
            $this->storeName() => $storeConfig
        ];
    }

    /**
     * Return store name which is used as configuration ID.
     *
     * @return string
     */
    public function storeName()
    {
        /**
         * User id used as store name
         */
        return (string) $this->user->id;
    }

    /**
     * Gets Amazon Inventory List for user
     *
     * @param User $user
     * @return array|null
     */
    public function getSupply()
    {
        $storeName = $this->storeName();

        try {
            $obj = new AmazonInventoryList($storeName);
            $obj->setUseToken(); //tells the object to automatically use tokens right away
            $obj->setStartTime("- 24 hours");
            $obj->fetchInventoryList(); //this is what actually sends the request
            return $obj->getSupply();
        } catch (\Exception $ex) {
            echo 'There was a problem with the Amazon library. Error: '.$ex->getMessage();
        }
    }

    public function requestListing()
    {
        RequestReport::queue(
            $this->storeName(),
            '_GET_MERCHANT_LISTINGS_DATA_'
        );
    }
}
