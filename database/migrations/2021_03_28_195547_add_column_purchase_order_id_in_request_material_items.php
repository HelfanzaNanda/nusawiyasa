<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPurchaseOrderIdInRequestMaterialItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_material_items', function (Blueprint $table) {
            $table->foreignId('purchase_order_id')->nullable()
            ->after('request_material_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_material_items', function (Blueprint $table) {
            $table->dropColumn('purchase_order_id');
        });
    }
}
