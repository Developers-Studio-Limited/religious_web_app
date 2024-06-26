<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNekiCounterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('neki_counter', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('amaal_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('neki_count')->nullable();
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
        Schema::dropIfExists('neki_counter');
    }
}
