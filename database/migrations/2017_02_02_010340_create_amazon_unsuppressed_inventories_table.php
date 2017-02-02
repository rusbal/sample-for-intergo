<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmazonUnsuppressedInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amazon_unsuppressed_inventories', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('sku')->unsigned();
            $table->string('fnsku');
            $table->string('asin');
            $table->string('product_name');
            $table->string('condition');
            $table->decimal('your_price', 9, 2);
            $table->string('mfn_listing_exists');
            $table->integer('mfn_fulfillable_quantity');
            $table->string('afn_listing_exists');
            $table->integer('afn_warehouse_quantity');
            $table->integer('afn_fulfillable_quantity');
            $table->integer('afn_unsellable_quantity');
            $table->integer('afn_reserved_quantity');
            $table->integer('afn_total_quantity');
            $table->decimal('per_unit_volume', 9, 2);
            $table->integer('afn_inbound_working_quantity');
            $table->integer('afn_inbound_shipped_quantity');
            $table->integer('afn_inbound_receiving_quantity');

            $table->timestamps();

            $table->index('asin', 'asin_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amazon_unsuppressed_inventories', function (Blueprint $table) {
            $table->dropIndex('asin_index');
        });
        Schema::dropIfExists('amazon_unsuppressed_inventories');
    }
}
