<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTaskTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_task_times', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_task_id')->nullable()->unsigned();
            $table->foreign('user_task_id')->references('id')->on('user_tasks')->onDelete('cascade');
            $table->bigInteger('category_detail_id')->nullable()->unsigned();
            $table->foreign('category_detail_id')->references('id')->on('categories_details')->onDelete('cascade');
            $table->timestamp('time')->nullable();
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
        Schema::dropIfExists('user_task_times');
    }
}
