<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToAmaalCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amaal_categories', function (Blueprint $table) {
            $table->bigInteger('created_by_admin')->nullable()->unsigned();
            $table->foreign('created_by_admin')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('created_by_user')->nullable()->unsigned();
            $table->foreign('created_by_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amaal_categories', function (Blueprint $table) {
            //
        });
    }
}
