<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToMarhoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_marhooms', function (Blueprint $table) {
            $table->dateTime('georgian_birth_date')->after('relation')->nullable();
            $table->dateTime('georgian_death_date')->after('relation')->nullable();
            $table->dateTime('hijri_birth_date')->after('relation')->nullable();
            $table->dateTime('hijri_death_date')->after('relation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_marhooms', function (Blueprint $table) {
            
        });
    }
}
