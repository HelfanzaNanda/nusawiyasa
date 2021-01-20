<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->string('fullname')->nullable();
            $table->string('email')->nullable();
            $table->string('bank_account')->nullable();
            $table->date('date_birth')->nullable();
            $table->string('place_birth')->nullable();
            $table->date('joined_at')->nullable();
            $table->date('resign_at')->nullable();
            $table->string('avatar')->nullable();
            $table->string('employe_status')->nullable();
            $table->string('gender')->nullable();
            $table->string('religion')->nullable();
            $table->string('mariage_status')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('identity_type')->nullable();
            $table->string('identity_card_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('emergency_number')->nullable();
            $table->string('emergency_name')->nullable();
            $table->string('emergency_relation')->nullable();
            $table->string('current_address_kecamatan')->nullable();
            $table->string('current_address_kelurahan')->nullable();
            $table->string('current_address_rt')->nullable();
            $table->string('current_address_rw')->nullable();
            $table->string('current_address_province')->nullable();
            $table->string('current_address_city')->nullable();
            $table->string('current_address_street')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('nik')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('blood_type')->nullable();
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
        Schema::dropIfExists('employes');
    }
}
