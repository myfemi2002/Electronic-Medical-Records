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
        Schema::create('laying_hands_requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->text('laying_hands_request');
            $table->text('reason_to_see_go');
            
            // RCCG Member specific fields (nullable for non-members)
            $table->string('parish')->nullable();
            $table->string('province')->nullable();
            $table->string('region')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('contact_address')->nullable();
            $table->string('prayer_category')->nullable();
            $table->string('preferred_communication')->nullable(); // email, sms, both
            $table->string('service_attended')->nullable();
            $table->string('how_heard_about_program')->nullable();
            $table->text('additional_notes')->nullable();
            $table->string('state'); // For non-members
            
            // System fields
            $table->boolean('is_rccg_member')->default(false);
            $table->enum('status', ['pending', 'approved', 'declined', 'notified', 'treated'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('treated_at')->nullable();
            $table->integer('approved_by')->nullable();
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index('email');
            $table->index('phone');
            $table->index('status');
            $table->index('is_rccg_member');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laying_hands_requests');
    }
};
