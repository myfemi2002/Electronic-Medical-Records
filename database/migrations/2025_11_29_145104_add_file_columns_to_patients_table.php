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
        Schema::table('patients', function (Blueprint $table) {
            $table->timestamp('last_consultancy_date')->nullable()->after('file_closed_at');
            $table->timestamp('consultancy_expires_at')->nullable()->after('last_consultancy_date');
            $table->boolean('consultancy_active')->default(false)->after('consultancy_expires_at');
            $table->integer('total_consultancy_paid')->default(0)->after('consultancy_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'last_consultancy_date',
                'consultancy_expires_at',
                'consultancy_active',
                'total_consultancy_paid'
            ]);
        });
    }
};