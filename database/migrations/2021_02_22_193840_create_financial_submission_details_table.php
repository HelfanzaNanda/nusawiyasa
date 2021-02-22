<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialSubmissionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_submission_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('financial_submission_id')->unsigned();
            $table->string('value');
            $table->integer('qty');
            $table->string('unit');
            $table->integer('price');
            $table->integer('total_price');
            $table->text('note');
            $table->timestamps();

            $table->foreign('financial_submission_id')->references('id')->on('financial_submissions')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('financial_submission_details');
    }
}
