<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmazonListingRankHistoryPopulationTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER `amazon_listing_rank_history_population` BEFORE UPDATE ON `amazon_merchant_listings` FOR EACH ROW IF OLD.asin1 <=> NEW.asin1 THEN
                INSERT INTO amazon_listing_rank_histories
                    ( asin, sales_rank, updated_at )
                VALUES
                    ( NEW.asin1, NEW.sales_rank, NEW.updated_at );
            END IF
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `amazon_listing_rank_history_population`');
    }
}
