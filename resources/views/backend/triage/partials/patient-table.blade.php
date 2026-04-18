{{-- resources/views/backend/triage/partials/patient-table.blade.php --}}
{{-- DARK MODE VERSION --}}

<div class="table-responsive">
    <table class="table table-dark-mode table-hover">
        <thead>
            <tr>
                <th class="text-white">Queue #</th>
                <th class="text-white">Time</th>
                <th class="text-white">Patient</th>
                <th class="text-white">Gender/Age</th>
                <th class="text-white">Priority</th>
                @if($showAll ?? false)
                <th class="text-white">Status</th>
                @endif
                <th class="text-white">Progress</th>
                <th class="text-white">Wait Time</th>
                <th class="text-white">Assigned To</th>
                <th class="text-white">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($patients as $patient)
            <tr class="{{ $patient->priority === 'critical' ? 'table-danger-dark' : ($patient->priority === 'moderate' ? 'table-warning-dark' : ($patient->status === 'completed' ? 'table-success-dark' : '')) }}">
                <td>
                    <span class="badge bg-dark border border-light text-info">{{ $patient->queue_number }}</span>
                </td>
                <td class="text-info">
                    <small>{{ $patient->joined_queue_at->format('H:i') }}</small>
                </td>
                <td>
                    <strong class="text-info">{{ $patient->patient->full_name }}</strong><br>
                    <small class="text-muted">{{ $patient->patient->card_number }}</small>
                </td>
                <td>
                    @if($patient->patient->gender === 'Male')
                        <span class="badge bg-primary">M</span>
                    @else
                        <span class="badge" style="background-color: #e91e63;">F</span>
                    @endif
                    <span class="text-info">{{ $patient->patient->age }}yrs</span>
                </td>
                <td>{!! $patient->priority_badge !!}</td>
                
                @if($showAll ?? false)
                <td>{!! $patient->status_badge !!}</td>
                @endif
                
                <td>
                    @if(!$patient->vitals)
                        <span class="badge bg-secondary">
                            <i class="fa fa-hourglass"></i> No Vitals
                        </span>
                    @elseif(!$patient->assessment)
                        <span class="badge bg-info">
                            <i class="fa fa-heartbeat"></i> Vitals Only
                        </span>
                    @elseif($patient->status === 'forwarded')
                        <span class="badge bg-success">
                            <i class="fa fa-check-double"></i> Forwarded
                        </span>
                    @else
                        <span class="badge bg-warning text-dark">
                            <i class="fa fa-clipboard-check"></i> Assessed
                        </span>
                    @endif
                </td>
                
                <td>
                    <span class="badge bg-warning text-dark">
                        {{ $patient->wait_time_formatted }}
                    </span>
                </td>
                
                <td>
                    @if($patient->assignedStaff)
                        <small class="text-info">{{ $patient->assignedStaff->name }}</small>
                        @if($patient->assigned_staff_role)
                            <br><span class="badge bg-info">{{ ucfirst($patient->assigned_staff_role) }}</span>
                        @endif
                    @else
                        <span class="text-muted">Not assigned</span>
                    @endif
                </td>
                
                <td>
                    <div class="btn-group" role="group">
                        @if($patient->status === 'forwarded')
                            <!-- Patient already forwarded -->
                            <button class="btn btn-sm btn-success" disabled>
                                <i class="fa fa-check"></i> Forwarded
                            </button>
                            
                        @elseif(!$patient->vitals)
                            <!-- No vitals yet - Capture Vitals -->
                            <a href="{{ route('admin.triage.capture-vitals', $patient->id) }}" 
                               class="btn btn-sm btn-primary" 
                               title="Capture Vitals">
                                <i class="fa fa-heartbeat"></i> Capture Vitals
                            </a>
                            
                        @elseif(!$patient->assessment)
                            <!-- Vitals captured but no assessment - Can Resume -->
                            <a href="{{ route('admin.triage.assessment', $patient->id) }}" 
                               class="btn btn-sm btn-success" 
                               title="Complete Assessment">
                                <i class="fa fa-clipboard-check"></i> Complete Assessment
                            </a>
                            
                            <!-- Option to recapture vitals -->
                            <a href="{{ route('admin.triage.capture-vitals', $patient->id) }}" 
                               class="btn btn-sm btn-outline-light" 
                               title="Recapture Vitals">
                                <i class="fa fa-redo"></i>
                            </a>
                            
                        @else
                            <!-- Assessment exists but not forwarded yet - Can edit assessment -->
                            <a href="{{ route('admin.triage.assessment', $patient->id) }}" 
                               class="btn btn-sm btn-warning text-dark" 
                               title="Edit Assessment">
                                <i class="fa fa-edit"></i> Edit Assessment
                            </a>
                            
                            <!-- Option to recapture vitals -->
                            <a href="{{ route('admin.triage.capture-vitals', $patient->id) }}" 
                               class="btn btn-sm btn-outline-light" 
                               title="Recapture Vitals">
                                <i class="fa fa-redo"></i>
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center py-5 empty-state-dark">
                    <i class="fa fa-inbox fa-3x mb-3"></i>
                    <p>No patients in this category</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
/* Additional table styling for better dark mode */
.table-dark-mode {
    --bs-table-bg: #212529;
    --bs-table-striped-bg: #2c3136;
    --bs-table-hover-bg: #323539;
    --bs-table-border-color: #495057;
}

.table-dark-mode tbody tr {
    border-color: #495057;
}

/* Improve button visibility on dark background */
.btn-outline-light {
    color: #f8f9fa;
    border-color: #6c757d;
}

.btn-outline-light:hover {
    color: #000;
    background-color: #f8f9fa;
    border-color: #f8f9fa;
}
</style>