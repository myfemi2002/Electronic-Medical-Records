@extends('admin.admin_master')
@section('title', 'Visit Details')
@section('admin')
<div class="page-header">
    <h1>Visit {{ $visit->visit_number }}</h1>
    <p>{{ $visit->patient->full_name }} • Current stage: {{ ucfirst($visit->current_stage) }}</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="stats-card"><h6>Status</h6><h3>{{ ucfirst($visit->status) }}</h3></div></div>
    <div class="col-md-4"><div class="stats-card"><h6>Chief Complaint</h6><h3 class="fs-6">{{ $visit->chief_complaint ?: 'Not recorded' }}</h3></div></div>
    <div class="col-md-4"><div class="stats-card"><h6>Invoice Balance</h6><h3>N{{ number_format(optional($visit->invoice)->balance ?? 0, 2) }}</h3></div></div>
</div>

<div class="card mb-4">
    <div class="card-header"><h5 class="card-title">Visit Timeline</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>From</th><th>To</th><th>Status</th><th>Note</th><th>Time</th></tr></thead>
                <tbody>
                    @forelse($visit->stageLogs as $log)
                        <tr>
                            <td>{{ $log->from_stage ?: 'Start' }}</td>
                            <td>{{ $log->to_stage }}</td>
                            <td>{{ $log->status }}</td>
                            <td>{{ $log->note }}</td>
                            <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">No stage logs yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="card-title">Billing</h5></div>
            <div class="card-body">
                @if($visit->invoice)
                    <p><strong>{{ $visit->invoice->invoice_number }}</strong></p>
                    <p>Total: N{{ number_format($visit->invoice->total, 2) }}</p>
                    <p>Paid: N{{ number_format($visit->invoice->amount_paid, 2) }}</p>
                    <p>Balance: N{{ number_format($visit->invoice->balance, 2) }}</p>
                @else
                    <p class="text-muted">Invoice not created yet.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="card-title">Clinical Summary</h5></div>
            <div class="card-body">
                <p><strong>Diagnosis:</strong> {{ $visit->encounter->diagnosis ?? 'Pending' }}</p>
                <p><strong>Disposition:</strong> {{ $visit->encounter->disposition ?? 'Pending' }}</p>
                <p><strong>Orders:</strong> {{ $visit->serviceOrders->count() }}</p>
                <p><strong>Prescriptions:</strong> {{ $visit->prescriptions->count() }}</p>
                <p><strong>Nursing Notes:</strong> {{ $visit->nursingNotes->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
