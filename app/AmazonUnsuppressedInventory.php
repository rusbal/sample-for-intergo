<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AmazonUnsuppressedInventory extends Model
{
    protected $fillable = [
        'sku',
        'fnsku',
        'asin',
        'product_name',
        'condition',
        'your_price',
        'mfn_listing_exists',
        'mfn_fulfillable_quantity',
        'afn_listing_exists',
        'afn_warehouse_quantity',
        'afn_fulfillable_quantity',
        'afn_unsellable_quantity',
        'afn_reserved_quantity',
        'afn_total_quantity',
        'per_unit_volume',
        'afn_inbound_working_quantity',
        'afn_inbound_shipped_quantity',
        'afn_inbound_receiving_quantity',
    ];
}
