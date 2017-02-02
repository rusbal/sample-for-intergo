<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeItemNoteFromStringToText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amazon_merchant_listings', function (Blueprint $table) {
            $table->text('item_note')->change();
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
            $table->string('item_note')->change();
        });
    }
}
