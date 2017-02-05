<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCacheSizeToMediumText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_caches', function (Blueprint $table) {

            $mediumTextSize = 16777215;

            $table->string('data', $mediumTextSize)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /**
         * WARNING: THIS CODE DOES NOT WORK.
         *
         * `data` does not rollback to text type, so it stays as mediumText.
         * So far it doesn't give any problem.
         */
        Schema::table('report_caches', function (Blueprint $table) {

            $textSize = 65535;

            $table->string('data', $textSize)->change();
        });
    }
}
