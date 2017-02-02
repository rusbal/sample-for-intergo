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
            $table->string('product-name');
            $table->string('condition');
            $table->decimal('your-price', 9, 2);
            $table->string('mfn-listing-exists');
            $table->string('mfn-fulfillable-quantity');
            $table->string('afn-listing-exists');
            $table->integer('afn-warehouse-quantity');
            $table->integer('afn-fulfillable-quantity');
            $table->integer('afn-unsellable-quantity');
            $table->integer('afn-reserved-quantity');
            $table->integer('afn-total-quantity');
            $table->decimal('per-unit-volume', 9, 2);
            $table->integer('afn-inbound-working-quantity');
            $table->integer('afn-inbound-shipped-quantity');
            $table->integer('afn-inbound-receiving-quantity');

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
