<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('visit_number')->unique();
            $table->string('visit_type')->default('outpatient');
            $table->string('current_stage')->default('records');
            $table->string('status')->default('registered');
            $table->boolean('is_emergency')->default(false);
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('cashier_cleared_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('triaged_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('discharged_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('queued_for_cashier_at')->nullable();
            $table->timestamp('cashier_cleared_at')->nullable();
            $table->timestamp('triaged_at')->nullable();
            $table->timestamp('doctor_seen_at')->nullable();
            $table->timestamp('discharged_at')->nullable();
            $table->text('chief_complaint')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['current_stage', 'status']);
            $table->index(['patient_id', 'created_at']);
        });

        Schema::create('visit_stage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->string('from_stage')->nullable();
            $table->string('to_stage');
            $table->string('status');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->string('status')->default('draft');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->decimal('balance', 12, 2)->default(0);
            $table->string('payer_type')->default('self');
            $table->string('payment_status')->default('unpaid');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('service_name');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('line_total', 12, 2);
            $table->string('category')->default('consultation');
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('receipt_number')->unique();
            $table->string('payment_method');
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('confirmed');
            $table->string('reference')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->text('reason');
            $table->foreignId('refunded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
        });

        Schema::create('clinical_encounters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('subjective')->nullable();
            $table->text('objective')->nullable();
            $table->text('assessment')->nullable();
            $table->text('plan')->nullable();
            $table->string('icd10_code')->nullable();
            $table->string('diagnosis')->nullable();
            $table->string('disposition')->default('ongoing');
            $table->text('referral_notes')->nullable();
            $table->text('discharge_notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('clinical_encounters')->nullOnDelete();
            $table->string('drug_name');
            $table->string('dosage');
            $table->string('frequency');
            $table->string('duration');
            $table->text('instructions')->nullable();
            $table->string('status')->default('pending');
            $table->foreignId('prescribed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('dispensed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('dispensed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('sku')->unique();
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('reorder_level')->default(0);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('clinical_encounters')->nullOnDelete();
            $table->string('service_type');
            $table->string('request_name');
            $table->text('instructions')->nullable();
            $table->string('status')->default('requested');
            $table->string('sample_barcode')->nullable();
            $table->text('result_text')->nullable();
            $table->text('report_text')->nullable();
            $table->string('image_path')->nullable();
            $table->string('template_name')->nullable();
            $table->text('comparison_notes')->nullable();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['service_type', 'status']);
        });

        Schema::create('nursing_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('nurse_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note');
            $table->text('medication_administration')->nullable();
            $table->text('procedures')->nullable();
            $table->string('bed_allocation')->nullable();
            $table->text('fluid_balance')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::table('triage_queue', function (Blueprint $table) {
            $table->foreignId('visit_id')->nullable()->after('patient_id')->constrained('visits')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('triage_queue', function (Blueprint $table) {
            $table->dropForeign(['visit_id']);
            $table->dropColumn('visit_id');
        });

        Schema::dropIfExists('nursing_notes');
        Schema::dropIfExists('service_orders');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('clinical_encounters');
        Schema::dropIfExists('payment_refunds');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('visit_stage_logs');
        Schema::dropIfExists('visits');
    }
};
