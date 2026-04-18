@extends('admin.admin_master')
@section('title', 'Pharmacy Workspace')
@section('admin')
<div class="page-header">
    <h1>Pharmacy Workspace</h1>
    <p>{{ $visit->patient->full_name }} • {{ $visit->visit_number }}</p>
</div>
<div class="card">
    <div class="card-header"><h5 class="card-title">Prescriptions</h5></div>
    <div class="card-body">
        @forelse($visit->prescriptions as $prescription)
            <div class="border rounded p-3 mb-3">
                <strong>{{ $prescription->drug_name }}</strong>
                <div>{{ $prescription->dosage }} • {{ $prescription->frequency }} • {{ $prescription->duration }}</div>
                <div class="small text-muted">{{ $prescription->instructions }}</div>
            </div>
        @empty
            <p class="text-muted mb-0">No prescriptions available for this visit.</p>
        @endforelse
    </div>
</div>
@endsection
