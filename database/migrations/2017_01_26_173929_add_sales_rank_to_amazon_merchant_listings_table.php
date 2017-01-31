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
            $table->integer('sales_rank')->nullable()->unsigned()->after('will_monitor');
            $table->integer('maximum_offer_quantity')->nullable()->unsigned()->after('sales_rank');
            $table->decimal('minimum_advertized_price', 9, 2)
                ->nullable()->unsigned()->after('maximum_offer_quantity');
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
            $table->dropColumn('maximum_offer_quantity');
            $table->dropColumn('minimum_advertized_price');
        });
    }
}
