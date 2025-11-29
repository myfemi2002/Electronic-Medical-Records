<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            
            $table->id();
            $table->string('card_number')->unique();
            $table->string('patient_lastname');
            $table->string('patient_firstname');
            $table->string('patient_phone');
            $table->string('patient_occupation')->nullable();
            $table->string('patient_religion');
            $table->enum('patient_gender', ['Male', 'Female']);
            $table->string('patient_status');
            $table->date('patient_dob');
            $table->string('patient_type');
            $table->string('patient_hmo')->nullable();
            $table->string('patient_nationality')->nullable();
            $table->text('patient_address')->nullable();
            $table->string('image')->nullable();
            $table->string('patient_kin_name')->nullable();
            $table->string('kin_relationship')->nullable();
            $table->string('patient_kin_phone')->nullable();
            $table->text('patient_kin_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
