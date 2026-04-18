@extends('admin.admin_master')
@section('title', 'Visit History')
@section('admin')
<div class="page-header">
    <h1>Visit History</h1>
    <p>Track patient visits, billing state, and movement across the HMS workflow.</p>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Visit</th>
                        <th>Patient</th>
                        <th>Stage</th>
                        <th>Status</th>
                        <th>Invoice</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visits as $visit)
                        <tr>
                            <td>{{ $visit->visit_number }}</td>
                            <td>{{ $visit->patient->full_name }}</td>
                            <td>{{ ucfirst($visit->current_stage) }}</td>
                            <td>{{ ucfirst($visit->status) }}</td>
                            <td>{{ $visit->invoice?->invoice_number ?: 'Pending' }}</td>
                            <td class="text-end"><a class="btn btn-sm btn-primary" href="{{ route('admin.hms.visits.show', $visit) }}">Open</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No visits recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $visits->links() }}</div>
    </div>
</div>
@endsection
