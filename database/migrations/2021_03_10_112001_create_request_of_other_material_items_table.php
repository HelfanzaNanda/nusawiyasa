<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestOfOtherMaterialItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_of_other_material_items', function (Blueprint $table) {
            $table->id();
            $table->integer('request_of_other_material_id');
            //$table->integer('inventory_id');
            $table->string('inventory_name');
            $table->string('brand');
            $table->string('unit');
            $table->integer('qty');
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
        Schema::dropIfExists('request_of_other_material_items');
    }
}
