<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmazonMerchantListingPriceViolationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amazon_merchant_listing_price_violations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('asin')->default('');
            $table->decimal('seller_price', 13, 2);
            $table->decimal('minimum_advertized_price', 9, 2);
            $table->string('merchant_id', 45)->default('');
            $table->string('amazon_unique_id', 45)->default('');
            $table->dateTime('amazon_publish_time');
            $table->timestamp('notification_sent_at')->nullable();
            $table->timestamps();
        });

        // INDEX
        Schema::table('amazon_merchant_listing_price_violations', function (Blueprint $table) {
            $table->index(['asin', 'merchant_id'], 'asin_merchant_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // INDEX
        Schema::table('amazon_merchant_listing_price_violations', function (Blueprint $table) {
            $table->dropIndex('asin_merchant_index');
        });

        Schema::dropIfExists('amazon_merchant_listing_price_violations');
    }
}
