@extends('admin.admin_master')
@section('title', 'Visit Flow')
@section('admin')
<div class="page-header">
    <h1>Records Visit Flow</h1>
    <p>Create visits, push them into cashier, and track the end-to-end patient movement.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stats-card"><h6>Records Queue</h6><h3>{{ $stats['registered'] }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>Awaiting Payment</h6><h3>{{ $stats['awaiting_payment'] }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>In Triage</h6><h3>{{ $stats['in_triage'] }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>Open Visits</h6><h3>{{ $stats['active'] }}</h3></div></div>
</div>

<div class="card mb-4">
    <div class="card-header"><h5 class="card-title">Recent Visits</h5></div>
    <div class="card-body">
        @include('backend.hms.partials.visit-table', ['visits' => $visits, 'route' => fn($visit) => route('admin.hms.visits.show', $visit)])
        <div class="mt-3">{{ $visits->links() }}</div>
    </div>
</div>
@endsection
