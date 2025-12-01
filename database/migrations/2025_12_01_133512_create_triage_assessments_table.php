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
        Schema::create('triage_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('triage_queue_id')->constrained('triage_queue')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            
            // DYNAMIC STAFF - Any user can assess
            $table->foreignId('assessed_by')->constrained('users')->onDelete('cascade');
            $table->string('assessed_by_role')->nullable()->comment('Role of staff who did assessment');
            
            // Chief Complaints
            $table->text('chief_complaints')->nullable();
            $table->text('history_of_present_illness')->nullable();
            
            // Priority Level (from vital interpretation)
            $table->enum('priority_level', ['mild', 'moderate', 'critical'])->default('mild');
            
            // Assessment Notes
            $table->text('initial_assessment_notes')->nullable();
            $table->json('system_generated_suggestions')->nullable(); // Auto-suggestions from VitalInterpreter
            $table->text('nurse_notes')->nullable();
            
            // DYNAMIC FORWARDING - Can forward to any department
            $table->foreignId('forwarded_to_department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->foreignId('forwarded_to_staff_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('forwarded_to_staff_role')->nullable()->comment('Role of staff forwarded to');
            $table->timestamp('forwarded_at')->nullable();
            $table->text('forwarding_reason')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('priority_level');
            $table->index('forwarded_to_department_id'); // FIXED: Changed from 'forwarded_to' to actual column name
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('triage_assessments');
    }
};