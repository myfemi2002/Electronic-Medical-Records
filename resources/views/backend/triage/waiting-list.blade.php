{{-- resources/views/backend/triage/waiting-list.blade.php --}}
@extends('admin.admin_master')
@section('title', 'Triage Waiting List')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h3 class="page-title">
                    <i class="bi bi-list-ul me-2"></i>Triage Waiting List
                </h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.triage.index') }}">Triage Module</a></li>
                    <li class="breadcrumb-item active">Waiting List</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 col-12 mb-3">
            <div class="card border-start border-4 border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Waiting</h6>
                            <h2 class="mb-0">{{ $stats['waiting_count'] }}</h2>
                        </div>
                        <div class="stat-icon bg-info">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 mb-3">
            <div class="card border-start border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Critical</h6>
                            <h2 class="mb-0 text-danger">{{ $stats['critical_count'] }}</h2>
                        </div>
                        <div class="stat-icon bg-danger">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 mb-3">
            <div class="card border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Moderate</h6>
                            <h2 class="mb-0 text-warning">{{ $stats['moderate_count'] }}</h2>
                        </div>
                        <div class="stat-icon bg-warning">
                            <i class="bi bi-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 mb-3">
            <div class="card border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Avg Wait Time</h6>
                            <h2 class="mb-0">{{ round($stats['average_wait_time']) }} min</h2>
                        </div>
                        <div class="stat-icon bg-success">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Waiting List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-people me-2"></i>Patients Waiting ({{ $waitingList->count() }})
                            </h5>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-sm btn-primary" onclick="location.reload()">
                                <i class="bi bi-arrow-clockwise me-1"></i> Refresh Queue
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($waitingList->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Queue #</th>
                                    <th>Card Number</th>
                                    <th>Patient Name</th>
                                    <th>Gender</th>
                                    <th>Age</th>
                                    <th>Priority</th>
                                    <th>Wait Time</th>
                                    <th>Joined At</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($waitingList as $key => $queue)
                                <tr class="queue-item priority-{{ $queue->priority ?? 'pending' }}">
                                    <td>
                                        <strong class="text-primary">{{ $queue->queue_number }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $queue->patient->card_number }}</strong>
                                    </td>
                                    <td>
                                        {{ $queue->patient->full_name }}
                                    </td>
                                    <td>
                                        @if($queue->patient->patient_gender == 'Male')
                                            <span class="badge bg-primary">Male</span>
                                        @else
                                            <span class="badge bg-info">Female</span>
                                        @endif
                                    </td>
                                    <td>{{ $queue->patient->age }} yrs</td>
                                    <td>
                                        @if($queue->priority === 'critical')
                                            <span class="badge bg-danger">
                                                <i class="bi bi-exclamation-triangle me-1"></i>Critical
                                            </span>
                                        @elseif($queue->priority === 'moderate')
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-exclamation-circle me-1"></i>Moderate
                                            </span>
                                        @elseif($queue->priority === 'mild')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Mild
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-question-circle me-1"></i>Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-warning">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $queue->wait_time_formatted }}
                                        </span>
                                    </td>
                                    <td class="text-secondary small">
                                        {{ $queue->joined_queue_at->format('h:i A') }}
                                    </td>
                                    <td>
                                        {!! $queue->status_badge !!}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.triage.capture-vitals', $queue->id) }}" 
                                               class="btn btn-sm btn-primary" 
                                               title="Capture Vitals">
                                                <i class="bi bi-thermometer"></i>
                                            </a>
                                            <a href="{{ route('admin.records.patients.show', $queue->patient_id) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="View Patient">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 4rem; color: var(--text-muted);"></i>
                        <p class="text-muted mt-3">No patients in waiting list</p>
                        <p class="text-muted small">Patients will appear here after consultancy payment verification</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('css')
<style>
.stat-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    color: white;
    font-size: 28px;
}

.queue-item.priority-critical {
    border-left: 4px solid var(--danger-color);
}

.queue-item.priority-moderate {
    border-left: 4px solid var(--warning-color);
}

.queue-item.priority-mild {
    border-left: 4px solid var(--success-color);
}

.queue-item.priority-pending {
    border-left: 4px solid var(--text-muted);
}
</style>
@endpush

@push('scripts')
<script>
    // Auto-refresh every 2 minutes
    setInterval(function() {
        location.reload();
    }, 120000);
    
    // Show notification if there are critical patients
    @if($stats['critical_count'] > 0)
        showToast('{{ $stats["critical_count"] }} CRITICAL patient(s) in queue!', 'danger');
    @endif
</script>
@endpush