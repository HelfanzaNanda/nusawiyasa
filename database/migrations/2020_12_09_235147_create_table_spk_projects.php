<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSpkProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spk_projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('number');
            $table->string('dest_name');
            $table->date('date');
            $table->string('subject');
            $table->integer('customer_lot_id');
            $table->string('note')->nullable();
            $table->integer('created_by_user_id');
            $table->integer('approved_by_user_id');
            $table->integer('received_by_user_id');
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
        Schema::dropIfExists('spk_projects');
    }
}
