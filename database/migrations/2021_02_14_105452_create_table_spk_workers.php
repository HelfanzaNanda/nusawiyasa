<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSpkWorkers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spk_workers', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('number')->nullable();
            $table->date('date')->nullable();
            $table->string('filepath');
            $table->string('filename');
            $table->integer('customer_lot_id');
            $table->integer('created_by_user_id')->nullable();
            $table->integer('approved_by_user_id')->nullable();
            $table->integer('received_by_user_id')->nullable();
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
        Schema::dropIfExists('spk_workers');
    }
}
