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
        Schema::create('consultancy_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->string('receipt_number')->unique();
            $table->string('payment_method'); // Cash, POS, Transfer, HMO
            $table->decimal('amount_paid', 10, 2);
            $table->date('payment_date');
            $table->timestamp('consultancy_start_date')->nullable();
            $table->timestamp('consultancy_expiry_date')->nullable();
            $table->enum('status', ['active', 'expired'])->default('active');
            $table->text('verification_note')->nullable();
            $table->unsignedBigInteger('verified_by');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('cascade');

            // Indexes for faster queries
            $table->index('patient_id');
            $table->index('status');
            $table->index('consultancy_expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultancy_payments');
    }
};