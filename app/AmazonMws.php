<?php

namespace App;

use Four13\AmazonMws\RequestReport;
use Zaffar\AmazonMws\AmazonInventoryList;
use Four13\AmazonMws\ToDb\MerchantListing;
use Four13\AmazonMws\ToDb\MerchantSkuQuantity;
use Four13\AmazonMws\ToDb\UnsuppressedInventory;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class AmazonMws extends EloquentModel
{
    use ObjectTrait;

    const UNITED_STATES_MARKETPLACE_ID = 'ATVPDKIKX0DER';

    protected $fillable = [
        'merchant_id', 'marketplace_id', 'mws_auth_token'
    ];

    const REPORT_REQUEST_TYPES = [
        '_GET_MERCHANT_LISTINGS_ALL_DATA_',
        '_GET_AFN_INVENTORY_DATA_',
        '_GET_FBA_MYI_UNSUPPRESSED_INVENTORY_DATA_',
    ];

    const REPORT_DATA_HANDLERS = [
        '_GET_MERCHANT_LISTINGS_ALL_DATA_' => MerchantListing::class,
        '_GET_AFN_INVENTORY_DATA_' => MerchantSkuQuantity::class,
        '_GET_FBA_MYI_UNSUPPRESSED_INVENTORY_DATA_' => UnsuppressedInventory::class,
    ];

    public static $rules = [
        'merchant_id' => 'required|unique:amazon_mws|between:13,14',
        'mws_auth_token' => 'required',
    ];

    public static $messages = [
        'merchant_id.unique' => 'The merchant id is already in use, please contact support if this is in error.'
    ];

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
     * Return values in the format required by Zaffar/laravel5-amazon-mws.
     *
     * @return array
     */
    public function storeConfiguration()
    {
        $keyId = env('AMAZON_KEY_ID');
        $secretKey = env('AMAZON_SECRET_KEY');

        $storeConfig = [
            'merchantId' => $this->merchant_id,
            'marketplaceId' => $this->marketplace_id,
            'keyId' => $keyId,
            'secretKey' => $secretKey,
            'authToken'=> $this->mws_auth_token,
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

    /**
     * @return bool
     */
    public function requestListing()
    {
        return RequestReport::queue(
            $this->storeName(),
            '_GET_MERCHANT_LISTINGS_ALL_DATA_'
        );
    }

    public static function getDataHandler($reportType, $reportClass)
    {
        /**
         * Report data handler files location: Four13/AmazonMws/ToDb/*
         */

        if (array_key_exists($reportType, self::REPORT_DATA_HANDLERS)) {
            $handlerClass = '\\' . self::REPORT_DATA_HANDLERS[$reportType];
            return new $handlerClass($reportClass);
        }

        return false;
    }
}
