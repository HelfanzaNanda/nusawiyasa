<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableBankStatusToCustomerLots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_lots', function (Blueprint $table) {
            $table->integer('bank_status')->nullable();
            $table->string('bank_status_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_lots', function (Blueprint $table) {
            $table->dropColumn('bank_status');
            $table->dropColumn('bank_status_number');
        });
    }
}
