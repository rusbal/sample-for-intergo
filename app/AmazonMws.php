<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Peron\AmazonMws\AmazonInventoryList;

class AmazonMws extends Model
{
    use ObjectTrait;

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
        } catch (Exception $ex) {
            echo 'There was a problem with the Amazon library. Error: '.$ex->getMessage();
        }
    }
}
