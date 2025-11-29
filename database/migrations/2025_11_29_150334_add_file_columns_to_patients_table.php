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
            $table->string('receipt_number')->nullable()->after('total_consultancy_paid');
            $table->string('payment_method')->nullable()->after('receipt_number');
            $table->decimal('amount_paid', 10, 2)->nullable()->after('payment_method');
            $table->date('payment_date')->nullable()->after('amount_paid');
            $table->text('verification_note')->nullable()->after('payment_date');
            $table->unsignedBigInteger('verified_by')->nullable()->after('verification_note');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'receipt_number',
                'payment_method',
                'amount_paid',
                'payment_date',
                'verification_note',
                'verified_by',
                'verified_at'
            ]);
        });
    }
};