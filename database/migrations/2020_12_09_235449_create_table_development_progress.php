<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDevelopmentProgress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('development_progress', function (Blueprint $table) {
            $table->id();
            $table->integer('cluster_id');
            $table->integer('lot_id');
            $table->date('date');
            $table->integer('user_created_id');
            $table->integer('user_consultant_id');
            $table->integer('user_supervisor_id');
            $table->integer('percentage');
            $table->integer('customer_id');
            $table->boolean('customer_approval');
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('development_progress');
    }
}
