@extends('admin.admin_master')
@section('title', 'Cashier Reports')
@section('admin')
<div class="page-header">
    <h1>Cashier Reports</h1>
    <p>Billing volume, revenue mix, HMO collections, and refund monitoring.</p>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stats-card"><h6>Invoices Today</h6><h3>{{ $stats['invoices_today'] }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>Revenue Today</h6><h3>N{{ number_format($stats['revenue_today'], 2) }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>HMO Today</h6><h3>N{{ number_format($stats['hmo_payments_today'], 2) }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>Refunds Today</h6><h3>N{{ number_format($stats['refunds_today'], 2) }}</h3></div></div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Invoice</th><th>Patient</th><th>Visit</th><th>Total</th><th>Paid</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($recentInvoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->patient->full_name }}</td>
                            <td>{{ $invoice->visit->visit_number }}</td>
                            <td>N{{ number_format($invoice->total, 2) }}</td>
                            <td>N{{ number_format($invoice->amount_paid, 2) }}</td>
                            <td>{{ ucfirst($invoice->payment_status) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">No recent invoices.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
