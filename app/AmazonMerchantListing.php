<?php

namespace App;


class AmazonMerchantListing extends Model
{
    const DEFAULT_ORDER = [
        ['open_date', 'desc']
    ];

    const SEARCH_COLUMNS = [
        'seller_sku' => '= int',
        'asin1'      => 'like',
        'item_name'  => 'like',
    ];

    protected $fillable = [
        'user_id',
        'item_name',
        'item_description',
        'listing_id',
        'seller_sku',
        'price',
        'quantity',
        'open_date',
        'image_url',
        'item_is_marketplace',
        'product_id_type',
        'zshop_shipping_fee',
        'item_note',
        'item_condition',
        'zshop_category1',
        'zshop_browse_path',
        'zshop_storefront_feature',
        'asin1',
        'asin2',
        'asin3',
        'will_ship_internationally',
        'expedited_shipping',
        'zshop_boldface',
        'product_id',
        'bid_for_featured_placement',
        'add_delete',
        'pending_quantity',
        'fulfillment_channel',
        'will_monitor',
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
