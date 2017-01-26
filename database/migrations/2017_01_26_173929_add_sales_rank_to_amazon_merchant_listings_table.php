<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSalesRankToAmazonMerchantListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amazon_merchant_listings', function (Blueprint $table) {
            $table->integer('sales_rank')->nullable()->unsigned();
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
            $table->dropColumn('sales_rank');
        });
    }
}
