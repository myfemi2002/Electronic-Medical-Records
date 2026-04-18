<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th>Visit</th>
                <th>Patient</th>
                <th>Stage</th>
                <th>Status</th>
                <th>Created</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($visits as $visit)
                <tr>
                    <td>
                        <strong>{{ $visit->visit_number }}</strong>
                        @if($visit->is_emergency)
                            <span class="badge bg-danger ms-1">Emergency</span>
                        @endif
                    </td>
                    <td>
                        <div>{{ $visit->patient->full_name }}</div>
                        <small class="text-muted">{{ $visit->patient->card_number }}</small>
                    </td>
                    <td><span class="badge bg-info text-dark">{{ ucfirst(str_replace('_', ' ', $visit->current_stage)) }}</span></td>
                    <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $visit->status)) }}</span></td>
                    <td>{{ $visit->created_at->format('d M Y, h:i A') }}</td>
                    <td class="text-end">
                        <a href="{{ $route($visit) }}" class="btn btn-sm btn-primary">Open</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No records available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
