<?php

namespace App\Transformer;


class AmazonMerchantListingTransformer
{
    public function transform($listing) {
        return [
            'id' => $listing->id,
            'seller_sku' => $listing->seller_sku,
            'asin' => $listing->asin1,
            'item_name' => $listing->item_name,
            'quantity_available' => $listing->quantity_available,
            'will_monitor' => $listing->will_monitor,
        ];
    }
}