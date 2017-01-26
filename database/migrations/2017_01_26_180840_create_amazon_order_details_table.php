<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmazonOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amazon_order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('merchant_id', 45)->default('');
            $table->string('amazon_order_id', 25)->unique();
            $table->dateTime('purchase_date');
            $table->string('order_status', 45)->default('');
            $table->string('sales_channel', 45)->default('');
            $table->string('fulfillment_channel', 10)->default('');
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
        Schema::dropIfExists('amazon_order_details');
    }
}
