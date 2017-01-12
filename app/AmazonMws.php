<?php

namespace App;

use Four13\AmazonMws\RequestReport;
use Illuminate\Database\Eloquent\Model;
use Peron\AmazonMws\AmazonInventoryList;

class AmazonMws extends Model
{
    use ObjectTrait;

    const UNITED_STATES_MARKETPLACE_ID = 'ATVPDKIKX0DER';

    protected $fillable = [
        'merchant_id', 'marketplace_id', 'mws_auth_token'
    ];

    public static $rules = [
        'merchant_id' => 'required|size:14',
        'mws_auth_token' => 'required',
    ];

    private $gettableConfig = [
        'key_id' => 'amazon-mws.AMAZON_KEY_ID',
        'secret_key' => 'amazon-mws.AMAZON_SECRET_KEY',
    ];

    public function __get($key)
    {
        if (isset($this->gettableConfig[$key])) {
            return config($this->gettableConfig[$key]);
        }

        return parent::__get($key);
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
