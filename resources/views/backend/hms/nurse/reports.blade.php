@extends('admin.admin_master')
@section('title', 'Nurse Reports')
@section('admin')
<div class="page-header">
    <h1>Nurse Reports</h1>
    <p>Nursing documentation, ward activity, medication administration, and completed visit support.</p>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stats-card"><h6>Notes Today</h6><h3>{{ $stats['notes_today'] }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>Procedures Logged</h6><h3>{{ $stats['procedures_logged'] }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>Medication Admin Today</h6><h3>{{ $stats['medication_admin_today'] }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>Completed Visits Today</h6><h3>{{ $stats['completed_visits_today'] }}</h3></div></div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Patient</th><th>Visit</th><th>Bed</th><th>Procedures</th><th>Time</th></tr></thead>
                <tbody>
                    @forelse($recentNotes as $note)
                        <tr>
                            <td>{{ $note->visit->patient->full_name }}</td>
                            <td>{{ $note->visit->visit_number }}</td>
                            <td>{{ $note->bed_allocation ?: '-' }}</td>
                            <td>{{ $note->procedures ?: '-' }}</td>
                            <td>{{ $note->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">No recent nursing notes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
