<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableReceiptOfGoodsRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_of_goods_request', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->date('date');
            $table->integer('cluster_id');
            $table->integer('lot_id');
            $table->integer('approved_user_id')->default(0);
            $table->integer('known_user_id')->default(0);
            $table->integer('created_user_id')->default(0);
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
        Schema::dropIfExists('receipt_of_goods_request');
    }
}
