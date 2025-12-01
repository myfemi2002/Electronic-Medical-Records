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
        Schema::create('triage_queue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->string('queue_number')->unique();
            
            // DYNAMIC STAFF - Any user can be assigned to triage
            $table->foreignId('assigned_staff')->nullable()->constrained('users')->onDelete('set null');
            $table->string('assigned_staff_role')->nullable()->comment('Role of staff assigned to triage');
            
            $table->enum('status', ['waiting', 'in_progress', 'completed', 'forwarded'])->default('waiting');
            $table->enum('priority', ['mild', 'moderate', 'critical'])->nullable();
            $table->text('initial_complaint')->nullable();
            $table->timestamp('joined_queue_at')->useCurrent();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index('priority');
            $table->index('joined_queue_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('triage_queue');
    }
};