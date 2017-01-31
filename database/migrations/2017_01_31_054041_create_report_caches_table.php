<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportCachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_caches', function (Blueprint $table) {
            $table->increments('id');

            // Concatenated start_date + report + n_days + user
            // Example: 20170214.1.dailyrevenue.1
            $table->string('date_days_report_user_id')->unique();

            $table->string('data');

            // Contains separate entities of the concatenated contents of column: dates_report_user_id
            $table->date('start_date');
            $table->integer('n_days')->default(1);
            $table->string('report');
            $table->integer('user_id')->unsigned();

            $table->timestamps();

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
        Schema::table('report_caches', function (Blueprint $table) {
            $table->dropIndex('user_id_index');
        });
        Schema::dropIfExists('report_caches');
    }
}
