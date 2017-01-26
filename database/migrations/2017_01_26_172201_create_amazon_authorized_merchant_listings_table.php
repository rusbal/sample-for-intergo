<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmazonAuthorizedMerchantListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amazon_authorized_merchant_listings', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_user_id')->nullable()->unsigned();
            $table->integer('authorized_user_id')->nullable()->unsigned();
            $table->string('asin')->nullable();
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
        Schema::dropIfExists('amazon_authorized_merchant_listings');
    }
}
