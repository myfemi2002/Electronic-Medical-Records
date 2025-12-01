<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration adds a staff_type column to users table
     * to support different staff roles beyond admin/user
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add staff_type for specific role identification
            // This allows for: nurse, doctor, pharmacist, lab_tech, radiologist, etc.
            $table->string('staff_type')->nullable()->after('role')->comment('Specific staff role: nurse, doctor, pharmacist, lab_tech, radiologist, etc.');
            
            // DYNAMIC DEPARTMENT - Links to departments table
            $table->foreignId('department_id')->nullable()->after('staff_type')->constrained('departments')->onDelete('set null');
            
            // Add staff_id for employee identification
            $table->string('staff_id')->nullable()->unique()->after('department_id')->comment('Staff ID number: e.g., STF-001');
            
            // Add license_number for medical professionals
            $table->string('license_number')->nullable()->after('staff_id')->comment('Professional license number');
            
            // Add specialization for doctors
            $table->string('specialization')->nullable()->after('license_number')->comment('Medical specialization');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['staff_type', 'department_id', 'staff_id', 'license_number', 'specialization']);
        });
    }
};