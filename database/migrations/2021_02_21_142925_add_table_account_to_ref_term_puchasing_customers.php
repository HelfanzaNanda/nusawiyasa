<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableAccountToRefTermPuchasingCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_term_purchasing_customers', function (Blueprint $table) {
            $table->string('income_account')->nullable();
            $table->string('receivable_account')->nullable();
            $table->string('account_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref_term_purchasing_customers', function (Blueprint $table) {
            $table->dropColumn('income_account');
            $table->dropColumn('receivable_account');
            $table->dropColumn('account_type');
        });
    }
}
