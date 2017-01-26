<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmazonListingRankHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amazon_listing_rank_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('asin')->default('');
            $table->integer('sales_rank')->nullable()->unsigned();
            $table->integer('maximum_offer_quantity')->nullable()->unsigned();
            $table->decimal('minimum_advertized_price', 9, 2)->nullable()->unsigned();
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
        Schema::dropIfExists('amazon_listing_rank_histories');
    }
}
