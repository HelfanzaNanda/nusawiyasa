<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToRequestMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_materials', function (Blueprint $table) {
            $table->string('type');
            $table->integer('cluster_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_materials', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('cluster_id');
        });
    }
}
