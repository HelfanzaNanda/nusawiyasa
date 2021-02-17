<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableWageSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wage_submissions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('number');
            $table->decimal('total', 20, 4);
            $table->integer('cluster_id');
            $table->integer('created_by_user_id')->nullable();
            $table->integer('approved_by_user_id')->nullable();
            $table->integer('received_by_user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wage_submissions');
    }
}
