<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmazonMwsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amazon_mws', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->indexed()->unsigned();
            $table->string('merchant_id');
            $table->string('marketplace_id');
            $table->string('mws_auth_token');
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
        Schema::dropIfExists('amazon_mws');
    }
}
