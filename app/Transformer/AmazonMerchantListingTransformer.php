<?php

namespace App\Transformer;


class AmazonMerchantListingTransformer
{
    public function transform($listing) {
        return [
            'id' => $listing->id,
            'user_id' => $listing->user_id,
            'will_monitor' => $listing->will_monitor,
            'item_name' => $listing->item_name,
            'listing_id' => $listing->listing_id,
            'seller_sku' => $listing->seller_sku,
            'price' => $listing->price,
            'quantity_available' => $listing->quantity_available
        ];
    }
}