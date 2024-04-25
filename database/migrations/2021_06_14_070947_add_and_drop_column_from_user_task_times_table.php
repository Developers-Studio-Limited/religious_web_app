<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAndDropColumnFromUserTaskTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_task_times', function (Blueprint $table) {
            $table->dropForeign('user_task_times_task_id_foreign');
            $table->dropColumn('user_task_id');
        });
        Schema::table('user_task_times', function (Blueprint $table) {
            $table->bigInteger('task_id')->nullable()->unsigned();
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
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
