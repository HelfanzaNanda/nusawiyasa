<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRapItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rap_items', function (Blueprint $table) {
            $table->id();
            $table->integer('rap_id');
            $table->integer('inventory_id');
            $table->integer('qty');
            $table->decimal('price', 20, 4);
            $table->decimal('total', 20, 4);
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
        Schema::dropIfExists('rap_items');
    }
}
