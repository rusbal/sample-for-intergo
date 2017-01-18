<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amazon_request_histories', function (Blueprint $table) {
            $table->string('status');
        });

        DB::table('amazon_request_histories')->update(['status' => '_DONE_']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amazon_request_histories', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
