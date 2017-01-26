<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmazonOrderItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amazon_order_item_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('merchant_id', 45)->default('');
            $table->string('amazon_order_id', 50);
            $table->string('asin')->default('');
            $table->string('seller_sku', 100)->default('');
            $table->string('order_item_id', 20)->unique();
            $table->integer('order_quantity')->unsigned();
            $table->decimal('order_item_price', 9, 2);
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
        Schema::dropIfExists('amazon_order_item_details');
    }
}
