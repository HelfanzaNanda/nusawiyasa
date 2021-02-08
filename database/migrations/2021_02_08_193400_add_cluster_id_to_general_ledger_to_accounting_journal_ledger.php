<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClusterIdToGeneralLedgerToAccountingJournalLedger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounting_ledgers', function (Blueprint $table) {
            $table->integer('cluster_id');
        });

        Schema::table('accounting_journals', function (Blueprint $table) {
            $table->integer('cluster_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounting_ledgers', function (Blueprint $table) {
            $table->dropColumn('cluster_id');
        });

        Schema::table('accounting_journals', function (Blueprint $table) {
            $table->dropColumn('cluster_id');
        });
    }
}
