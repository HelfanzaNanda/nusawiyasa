<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerIdToCostAndTerms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_costs', function (Blueprint $table) {
            $table->integer('customer_id');
            $table->integer('lot_id');
        });

        Schema::table('customer_terms', function (Blueprint $table) {
            $table->integer('customer_id');
            $table->integer('lot_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_costs', function (Blueprint $table) {
            $table->dropColumn('customer_id');
            $table->dropColumn('lot_id');
        });

        Schema::table('customer_terms', function (Blueprint $table) {
            $table->dropColumn('customer_id');
            $table->dropColumn('lot_id');
        });
    }
}
