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
            $table->string('account')->nullable();
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
            $table->dropColumn('account');
            $table->dropColumn('account_type');
        });
    }
}
