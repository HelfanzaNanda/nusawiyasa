<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnNoFppToPurchaseOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('fpp_number')->nullable();
            $table->string('type')->nullable();
            $table->string('note')->nullable();
            $table->decimal('subtotal', 20, 4)->nullable();
            $table->decimal('tax', 20, 4)->nullable();
            $table->decimal('delivery', 20, 4)->nullable();
            $table->decimal('other', 20, 4)->nullable();
            $table->decimal('total', 20, 4)->nullable();
            $table->integer('approved_user_id')->default(0);
            $table->integer('known_user_id')->default(0);
            $table->integer('created_user_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn('fpp_number');
            $table->dropColumn('type');
            $table->dropColumn('note');
            $table->dropColumn('subtotal');
            $table->dropColumn('tax');
            $table->dropColumn('delivery');
            $table->dropColumn('other');
            $table->dropColumn('total');
            $table->dropColumn('approved_user_id');
            $table->dropColumn('known_user_id');
            $table->dropColumn('created_user_id');
        });
    }
}
