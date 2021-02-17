<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableWageSubmissionDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wage_submission_details', function (Blueprint $table) {
            $table->id();
            $table->integer('wage_submission_id');
            $table->integer('customer_lot_id');
            $table->string('description')->nullable();
            $table->string('note')->nullable();
            $table->integer('weekly_percentage');
            $table->decimal('weekly_cost', 20, 4);
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
        Schema::dropIfExists('wage_submission_details');
    }
}
