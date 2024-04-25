<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToJobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->bigInteger('amaal_subcategory_id')->nullable()->unsigned();
            $table->foreign('amaal_subcategory_id')->references('id')->on('amaals')->onDelete('cascade');
            $table->integer('quantity')->nullable();
            $table->enum('type',['free','paid'])->nullable();
            $table->timestamp('due_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            //
        });
    }
}
