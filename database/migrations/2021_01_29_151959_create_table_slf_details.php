<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSlfDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slf_details', function (Blueprint $table) {
            $table->id();
            $table->integer('slf_id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('filename')->nullable();
            $table->string('filepath')->nullable();
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
        Schema::dropIfExists('slf_details');
    }
}
