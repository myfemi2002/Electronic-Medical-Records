<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\TriageQueue;
use App\Models\Visit;
use Illuminate\Validation\ValidationException;

class VisitWorkflowService
{
    public const STAGE_RECORDS = 'records';
    public const STAGE_CASHIER = 'cashier';
    public const STAGE_TRIAGE = 'triage';
    public const STAGE_DOCTOR = 'doctor';
    public const STAGE_LAB = 'laboratory';
    public const STAGE_RADIOLOGY = 'radiology';
    public const STAGE_PHARMACY = 'pharmacy';
    public const STAGE_NURSE = 'nurse';
    public const STAGE_DISCHARGED = 'discharged';

    public function createVisit(array $data): Visit
    {
        $visit = Visit::create([
            'patient_id' => $data['patient_id'],
            'visit_number' => Visit::generateVisitNumber($data['is_emergency'] ?? false),
            'visit_type' => $data['visit_type'] ?? 'outpatient',
            'current_stage' => self::STAGE_RECORDS,
            'status' => 'registered',
            'is_emergency' => $data['is_emergency'] ?? false,
            'created_by' => auth()->id(),
            'chief_complaint' => $data['chief_complaint'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        $this->logStage($visit, null, self::STAGE_RECORDS, 'registered', 'Visit created in records');

        return $visit;
    }

    public function pushToCashier(Visit $visit, ?string $note = null): Visit
    {
        $this->assertCurrentStage($visit, [self::STAGE_RECORDS]);

        $this->moveVisit($visit, self::STAGE_CASHIER, 'awaiting_payment', [
            'queued_for_cashier_at' => now(),
        ], $note ?? 'Visit pushed from records to cashier');

        return $visit->refresh();
    }

    public function createInvoice(Visit $visit, array $items, string $payerType = 'self'): Invoice
    {
        $this->assertCurrentStage($visit, [self::STAGE_CASHIER]);

        $invoice = $visit->invoice ?: Invoice::create([
            'visit_id' => $visit->id,
            'patient_id' => $visit->patient_id,
            'invoice_number' => sprintf('INV-%s-%04d', now()->format('Ymd'), Invoice::count() + 1),
            'status' => 'draft',
            'payer_type' => $payerType,
            'created_by' => auth()->id(),
        ]);

        $invoice->items()->delete();

        $subtotal = 0;
        foreach ($items as $item) {
            $lineTotal = $item['quantity'] * $item['unit_price'];
            $subtotal += $lineTotal;

            $invoice->items()->create([
                'service_name' => $item['service_name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'line_total' => $lineTotal,
                'category' => $item['category'] ?? 'consultation',
            ]);
        }

        $invoice->update([
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'balance' => $subtotal,
            'status' => 'issued',
            'payment_status' => 'unpaid',
            'updated_by' => auth()->id(),
        ]);

        return $invoice->refresh();
    }

    public function confirmPayment(Visit $visit, array $data): Payment
    {
        $invoice = $visit->invoice;
        if (!$invoice) {
            throw ValidationException::withMessages([
                'invoice' => 'Generate an invoice before confirming payment.',
            ]);
        }

        if ($data['amount'] <= 0) {
            throw ValidationException::withMessages([
                'amount' => 'Payment amount must be greater than zero.',
            ]);
        }

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'visit_id' => $visit->id,
            'patient_id' => $visit->patient_id,
            'receipt_number' => sprintf('RCT-%s-%04d', now()->format('Ymd'), Payment::count() + 1),
            'payment_method' => $data['payment_method'],
            'amount' => $data['amount'],
            'status' => 'confirmed',
            'reference' => $data['reference'] ?? null,
            'received_by' => auth()->id(),
            'paid_at' => now(),
        ]);

        $paidAmount = $invoice->payments()->sum('amount');
        $balance = max($invoice->total - $paidAmount, 0);
        $invoice->update([
            'amount_paid' => $paidAmount,
            'balance' => $balance,
            'payment_status' => $balance === 0 ? 'paid' : 'partial',
            'status' => $balance === 0 ? 'paid' : 'issued',
            'confirmed_at' => $balance === 0 ? now() : null,
        ]);

        if ($balance === 0) {
            $this->moveVisit($visit, self::STAGE_TRIAGE, 'paid', [
                'cashier_cleared_at' => now(),
                'cashier_cleared_by' => auth()->id(),
            ], 'Cashier confirmed payment');

            TriageQueue::addFromVisit($visit);
        }

        return $payment;
    }

    public function markTriaged(Visit $visit): void
    {
        $this->assertCurrentStage($visit, [self::STAGE_TRIAGE]);

        $this->moveVisit($visit, self::STAGE_DOCTOR, 'triaged', [
            'triaged_at' => now(),
            'triaged_by' => auth()->id(),
        ], 'Patient forwarded from triage to doctor');
    }

    public function markDoctorSeen(Visit $visit): void
    {
        $this->assertCurrentStage($visit, [self::STAGE_DOCTOR]);

        $visit->update([
            'doctor_seen_at' => now(),
            'doctor_id' => auth()->id(),
            'status' => 'in_consultation',
        ]);
    }

    public function moveToStage(Visit $visit, string $stage, string $status, ?string $note = null): void
    {
        $this->moveVisit($visit, $stage, $status, [], $note);
    }

    public function discharge(Visit $visit, ?string $note = null): void
    {
        $this->moveVisit($visit, self::STAGE_DISCHARGED, 'discharged', [
            'discharged_at' => now(),
            'discharged_by' => auth()->id(),
        ], $note ?? 'Visit discharged');
    }

    public function assertCurrentStage(Visit $visit, array $allowedStages): void
    {
        if (!in_array($visit->current_stage, $allowedStages, true)) {
            throw ValidationException::withMessages([
                'visit' => 'This action is not allowed while the visit is in the ' . $visit->current_stage . ' stage.',
            ]);
        }
    }

    protected function moveVisit(Visit $visit, string $toStage, string $status, array $attributes = [], ?string $note = null): void
    {
        $fromStage = $visit->current_stage;

        $visit->update(array_merge($attributes, [
            'current_stage' => $toStage,
            'status' => $status,
        ]));

        $this->logStage($visit, $fromStage, $toStage, $status, $note);
    }

    protected function logStage(Visit $visit, ?string $fromStage, string $toStage, string $status, ?string $note = null): void
    {
        $visit->stageLogs()->create([
            'from_stage' => $fromStage,
            'to_stage' => $toStage,
            'status' => $status,
            'changed_by' => auth()->id(),
            'note' => $note,
        ]);
    }
}
