<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmazonSuppressedListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amazon_suppressed_listings', function (Blueprint $table) {
            $table->increments('id');

            $table->string('merchant_id', 25);
            $table->string('seller_sku', 50);
            $table->string('item_name');
            $table->string('asin', 25);
            $table->string('field_name', 100);
            $table->string('alert_type', 100);
            $table->string('current_value', 100);
            $table->dateTime('last_updated');
            $table->string('alert_name', 100);
            $table->string('status', 100);
            $table->string('explanation');

            $table->timestamps();

            /**
             * Indexes
             */
            $table->index('merchant_id', 'merchant_id_index');
            $table->index('asin', 'asin_index');
            $table->index('alert_type', 'alert_type_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amazon_suppressed_listings', function (Blueprint $table) {
            $table->dropIndex('merchant_id_index');
            $table->dropIndex('asin_index');
            $table->dropIndex('alert_type_index');
        });
        Schema::dropIfExists('amazon_suppressed_listings');
    }
}
