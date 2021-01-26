<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLotGalleries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lot_galleries', function (Blueprint $table) {
            $table->id();
            $table->integer('lot_id')->nullable();
            $table->string('filename')->nullable();
            $table->string('filepath')->nullable();
            $table->boolean('is_cover')->nullable();
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
        Schema::dropIfExists('lot_galleries');
    }
}
