<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToSubTaskDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_sub_task_details', function (Blueprint $table) {
            $table->bigInteger('user_task_time_id')->after('name')->nullable()->unsigned();
            $table->foreign('user_task_time_id')->references('id')->on('user_task_times')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_task_details', function (Blueprint $table) {
            //
        });
    }
}
