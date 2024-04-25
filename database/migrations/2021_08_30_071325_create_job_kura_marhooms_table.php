<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobKuraMarhoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_kura_marhooms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('job_kura_id')->nullable()->unsigned();
            $table->foreign('job_kura_id')->references('id')->on('job_kuras')->onDelete('cascade');
            $table->bigInteger('user_marhoom_id')->nullable()->unsigned();
            $table->foreign('user_marhoom_id')->references('id')->on('user_marhooms')->onDelete('cascade');
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
        Schema::dropIfExists('job_kura_marhooms');
    }
}
