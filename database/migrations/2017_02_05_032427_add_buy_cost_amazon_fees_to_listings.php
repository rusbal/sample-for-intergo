<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuyCostAmazonFeesToListings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amazon_merchant_listings', function (Blueprint $table) {
            $table->decimal('buy_cost', 9, 2);
            $table->decimal('amazon_fees', 9, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amazon_merchant_listings', function (Blueprint $table) {
            $table->dropColumn('buy_cost');
            $table->dropColumn('amazon_fees');
        });
    }
}
