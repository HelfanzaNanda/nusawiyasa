<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOwnerBankNumberToEmployesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employes', function (Blueprint $table) {
            $table->string('owner_bank_number')->nullable();
            $table->dropColumn('nik');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employes', function (Blueprint $table) {
            $table->dropColumn('owner_bank_number');
        });
    }
}
