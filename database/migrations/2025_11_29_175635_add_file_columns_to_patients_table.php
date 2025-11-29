<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration removes old consultancy fields that will now be tracked 
     * in the consultancy_payments table, and adds new simplified tracking fields.
     */
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Remove old fields (data will be in consultancy_payments table now)
            $table->dropColumn([
                'last_consultancy_date',
                'consultancy_expires_at', 
                'consultancy_active',
                'total_consultancy_paid'
            ]);
            
            // Add new simplified tracking fields
            $table->timestamp('current_consultancy_expires_at')->nullable()->after('file_closed_at');
            $table->boolean('has_active_consultancy')->default(false)->after('current_consultancy_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Remove new fields
            $table->dropColumn([
                'current_consultancy_expires_at',
                'has_active_consultancy'
            ]);
            
            // Restore old fields
            $table->timestamp('last_consultancy_date')->nullable()->after('file_closed_at');
            $table->timestamp('consultancy_expires_at')->nullable()->after('last_consultancy_date');
            $table->boolean('consultancy_active')->default(false)->after('consultancy_expires_at');
            $table->integer('total_consultancy_paid')->default(0)->after('consultancy_active');
        });
    }
};