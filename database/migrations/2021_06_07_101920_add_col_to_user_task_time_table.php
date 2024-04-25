<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToUserTaskTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_task_times', function (Blueprint $table) {
            $table->dropColumn('time');
           
        });
        Schema::table('user_task_times', function (Blueprint $table) {
            $table->time('time')->after('category_detail_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_task_times', function (Blueprint $table) {
            //
        });
    }
}
