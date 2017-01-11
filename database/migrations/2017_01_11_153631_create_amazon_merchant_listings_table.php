<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmazonMerchantListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amazon_merchant_listings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->indexed()->unsigned();

            /**
             * Amazon Merchant Listing Data
             *
                item-name:                   (string)     An Incomplete Education: 3,684 Things You Should Have Learned but Probably Di...
                item-description:            (string)
                listing-id:                  (string)     0925PSL5517
                seller-sku:                  (integer)    1003125372
                price:                       (decimal)    15.99
                quantity:                    (integer)
                open-date:                   (dateTimeTz) 2015-09-25 09:32:35 PDT
                image-url:                   (string)
                item-is-marketplace:         (char)       y
                product-id-type:             (integer)    1
                zshop-shipping-fee:          (decimal)
                item-note:                   (string)
                item-condition:              (integer)    2
                zshop-category1:             (string)
                zshop-browse-path:           (string)
                zshop-storefront-feature:    (string)
                asin1:                       (integer)    0345468902
                asin2:                       (integer)
                asin3:                       (integer)
                will-ship-internationally:   (integer)    2
                expedited-shipping:          (string)     Domestic
                zshop-boldface:              (string)
                product-id:                  (integer)    0345468902
                bid-for-featured-placement:  (string)
                add-delete:                  (string)
                pending-quantity:            (integer)
                fulfillment-channel:         (string)     AMAZON_NA
             */

            $table->string('item_name');
            $table->string('item_description');
            $table->string('listing_id');
            $table->integer('seller_sku');
            $table->decimal('price', 9, 2);
            $table->integer('quantity');
            $table->dateTimeTz('open_date');
            $table->string('image_url');
            $table->char('item_is_marketplace');
            $table->smallInteger('product_id_type');
            $table->decimal('zshop_shipping_fee', 9, 2);
            $table->string('item_note');
            $table->smallInteger('item_condition');
            $table->string('zshop_category1');
            $table->string('zshop_browse_path');
            $table->string('zshop_storefront_feature');
            $table->integer('asin1');
            $table->integer('asin2');
            $table->integer('asin3');
            $table->smallInteger('will_ship_internationally');
            $table->string('expedited_shipping');
            $table->string('zshop_boldface');
            $table->integer('product_id');
            $table->string('bid_for_featured_placement');
            $table->string('add_delete');
            $table->integer('pending_quantity');
            $table->string('fulfillment_channel');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amazon_merchant_listings');
    }
}
