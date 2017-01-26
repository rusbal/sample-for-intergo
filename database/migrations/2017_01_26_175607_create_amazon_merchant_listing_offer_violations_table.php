<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmazonMerchantListingOfferViolationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amazon_merchant_listing_offer_violations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('asin')->default('');
            $table->integer('offer_quantity')->unsigned();
            $table->integer('maximum_offer_quantity')->nullable()->unsigned();
            $table->string('amazon_unique_id', 45)->default('');
            $table->dateTime('amazon_publish_time');
            $table->timestamp('notification_sent_at')->nullable();
            $table->timestamps();
        });

        // INDEX
        Schema::table('amazon_merchant_listing_offer_violations', function (Blueprint $table) {
            $table->index('asin', 'asin_index');
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
        Schema::table('amazon_merchant_listing_offer_violations', function (Blueprint $table) {
            $table->dropIndex('asin_index');
        });

        Schema::dropIfExists('amazon_merchant_listing_offer_violations');
    }
}
