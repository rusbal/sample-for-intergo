<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmazonRequestHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amazon_request_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->indexed()->unsigned();
            $table->string('store_name');
            $table->string('request_id');
            $table->string('class');
            $table->string('type');
            $table->text('response');
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
        Schema::dropIfExists('amazon_request_histories');
    }
}
