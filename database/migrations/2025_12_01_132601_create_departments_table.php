<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * DYNAMIC DEPARTMENTS - Add unlimited departments anytime!
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'Triage', 'Emergency', 'Pharmacy'
            $table->string('code')->unique(); // e.g., 'TRG', 'EMG', 'PHM'
            $table->string('type')->nullable(); // 'medical', 'support', 'administrative'
            $table->text('description')->nullable();
            $table->string('location')->nullable(); // Building/Floor location
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('head_of_department_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('display_order')->default(0); // For sorting in lists
            $table->boolean('can_receive_triage_patients')->default(false); // For triage forwarding
            $table->boolean('requires_appointment')->default(false);
            $table->json('working_hours')->nullable(); // Store operating hours
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index('type');
            $table->index('can_receive_triage_patients');
        });

        // Seed default departments
        DB::table('departments')->insert([
            [
                'name' => 'Triage',
                'code' => 'TRG',
                'type' => 'medical',
                'description' => 'Emergency triage and initial assessment',
                'can_receive_triage_patients' => true,
                'status' => 'active',
                'display_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Emergency',
                'code' => 'EMG',
                'type' => 'medical',
                'description' => 'Emergency department for critical cases',
                'can_receive_triage_patients' => true,
                'status' => 'active',
                'display_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Outpatient',
                'code' => 'OPD',
                'type' => 'medical',
                'description' => 'Outpatient consultation and treatment',
                'can_receive_triage_patients' => true,
                'status' => 'active',
                'display_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pharmacy',
                'code' => 'PHM',
                'type' => 'support',
                'description' => 'Medication dispensing and pharmaceutical care',
                'can_receive_triage_patients' => true,
                'status' => 'active',
                'display_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laboratory',
                'code' => 'LAB',
                'type' => 'support',
                'description' => 'Medical laboratory testing and analysis',
                'can_receive_triage_patients' => true,
                'status' => 'active',
                'display_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Radiology',
                'code' => 'RAD',
                'type' => 'support',
                'description' => 'Imaging services - X-ray, CT, MRI, Ultrasound',
                'can_receive_triage_patients' => true,
                'status' => 'active',
                'display_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Inpatient',
                'code' => 'IPD',
                'type' => 'medical',
                'description' => 'Inpatient ward and admission',
                'can_receive_triage_patients' => false,
                'status' => 'active',
                'display_order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Records',
                'code' => 'REC',
                'type' => 'administrative',
                'description' => 'Medical records and patient registration',
                'can_receive_triage_patients' => false,
                'status' => 'active',
                'display_order' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Accounts',
                'code' => 'ACC',
                'type' => 'administrative',
                'description' => 'Billing and financial services',
                'can_receive_triage_patients' => false,
                'status' => 'active',
                'display_order' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};