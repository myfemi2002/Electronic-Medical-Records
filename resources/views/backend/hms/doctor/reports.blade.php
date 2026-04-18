@extends('admin.admin_master')
@section('title', 'Doctor Reports')
@section('admin')
<div class="page-header">
    <h1>Doctor Reports</h1>
    <p>Consultation volume, clinical outputs, prescriptions, and discharge activity.</p>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stats-card"><h6>Consultations Today</h6><h3>{{ $stats['consultations_today'] }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>Discharges Today</h6><h3>{{ $stats['discharges_today'] }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>Prescriptions Today</h6><h3>{{ $stats['prescriptions_today'] }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>Orders Today</h6><h3>{{ $stats['orders_today'] }}</h3></div></div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Patient</th><th>Doctor</th><th>Diagnosis</th><th>ICD-10</th><th>Disposition</th><th>Completed</th></tr></thead>
                <tbody>
                    @forelse($recentEncounters as $encounter)
                        <tr>
                            <td>{{ $encounter->patient->full_name }}</td>
                            <td>{{ $encounter->doctor->name ?? 'N/A' }}</td>
                            <td>{{ $encounter->diagnosis }}</td>
                            <td>{{ $encounter->icd10_code ?: '-' }}</td>
                            <td>{{ ucfirst($encounter->disposition) }}</td>
                            <td>{{ $encounter->completed_at?->format('d M Y, h:i A') ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">No recent consultations.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
