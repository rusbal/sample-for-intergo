<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToPriceViolationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amazon_merchant_listing_price_violations', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->after('id');

            $table->index('user_id', 'user_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amazon_merchant_listing_price_violations', function (Blueprint $table) {
            $table->dropIndex('user_id_index');

            $table->dropColumn('user_id');
        });
    }
}
