<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToRapRab extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rap', function (Blueprint $table) {
            $table->string('type')->after('title')->nullable();
        });

        Schema::table('rab', function (Blueprint $table) {
            $table->string('type')->after('title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rap', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('rab', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
