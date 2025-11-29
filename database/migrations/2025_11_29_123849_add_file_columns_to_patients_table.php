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
            $table->timestamp('file_opened_at')->nullable()->after('patient_kin_address');
            $table->enum('file_status', ['closed', 'open'])->default('closed')->after('file_opened_at');
            $table->unsignedBigInteger('opened_by')->nullable()->after('file_status');
            $table->timestamp('file_closed_at')->nullable()->after('opened_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['file_opened_at', 'file_status', 'opened_by', 'file_closed_at']);
        });
    }
};