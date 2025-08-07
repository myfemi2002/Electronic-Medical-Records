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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_banned')->default(false)->after('status');
            $table->timestamp('banned_at')->nullable()->after('is_banned');
            $table->text('ban_reason')->nullable()->after('banned_at');
            $table->unsignedBigInteger('banned_by')->nullable()->after('ban_reason');
            $table->timestamp('ban_lifted_at')->nullable()->after('banned_by');
            $table->unsignedBigInteger('ban_lifted_by')->nullable()->after('ban_lifted_at');
            $table->text('ban_lift_reason')->nullable()->after('ban_lifted_by');
            
            // Add foreign key constraints
            $table->foreign('banned_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('ban_lifted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
