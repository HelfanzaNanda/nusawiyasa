<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestOfOtherMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_of_other_materials', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('title');
            $table->string('subject'); //perihal
            $table->integer('spk_id');
            $table->date('date');
            $table->string('type');
            $table->integer('cluster_id');
            $table->integer('lot_id');
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
        Schema::dropIfExists('request_of_other_materials');
    }
}
