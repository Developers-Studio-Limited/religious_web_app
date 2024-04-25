<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmaalCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amaal_categories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('amaal_id')->unsigned();
            $table->foreign('amaal_id')->references('id')->on('amaals')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->enum('recurring', ['yes', 'no']);
            $table->enum('recurring_type', ['daily', 'weekly','monthly','yearly']);
            $table->json('recurring_detail')->nullable();
            $table->integer('naiki');
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
        Schema::dropIfExists('amaal_categories');
    }
}
