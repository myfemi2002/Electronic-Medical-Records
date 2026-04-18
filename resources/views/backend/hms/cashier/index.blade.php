@extends('admin.admin_master')
@section('title', 'Cashier Queue')
@section('admin')
<div class="page-header">
    <h1>Cashier Module</h1>
    <p>Issue invoices, receive payments, and move paid visits into triage.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stats-card"><h6>Queue</h6><h3>{{ $stats['queue'] }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>Paid Today</h6><h3>N{{ number_format($stats['paid_today'], 2) }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>Unpaid Invoices</h6><h3>{{ $stats['unpaid_invoices'] }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>Refunds Today</h6><h3>N{{ number_format($stats['refunds_today'], 2) }}</h3></div></div>
</div>

<div class="card">
    <div class="card-header"><h5 class="card-title">Waiting Visits</h5></div>
    <div class="card-body">
        @include('backend.hms.partials.visit-table', ['visits' => $visits, 'route' => fn($visit) => route('admin.cashier.show', $visit)])
    </div>
</div>
@endsection
