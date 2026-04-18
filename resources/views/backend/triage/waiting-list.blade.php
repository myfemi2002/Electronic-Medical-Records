@extends('admin.admin_master')
@section('title', 'Triage Waiting List')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="page-title">Triage Waiting List</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.triage.index') }}">Triage</a></li>
                    <li class="breadcrumb-item active">Waiting List</li>
                </ul>
            </div>
            <div class="col-sm-6 text-end">
                <button class="btn btn-primary" onclick="location.reload()">
                    <i class="fa fa-sync"></i> Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-0">{{ $stats['waiting_count'] }}</h4>
                            <p class="text-muted mb-0">Waiting</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-danger">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-0 text-danger">{{ $stats['critical_count'] }}</h4>
                            <p class="text-muted mb-0">Critical</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning">
                            <i class="fa fa-exclamation-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-0 text-warning">{{ $stats['moderate_count'] }}</h4>
                            <p class="text-muted mb-0">Moderate</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success">
                            <i class="fa fa-clock"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-0">{{ round($stats['average_wait_time']) }}</h4>
                            <p class="text-muted mb-0">Avg Wait (min)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Waiting List Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-list"></i> Patients in Queue ({{ $patients->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Queue #</th>
                                    <th>Time</th>
                                    <th>Patient</th>
                                    <th>Gender/Age</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Wait Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patients as $patient)
                                <tr class="{{ $patient->priority === 'critical' ? 'table-danger' : ($patient->priority === 'moderate' ? 'table-warning' : '') }}">
                                    <td>
                                        <span class="badge bg-dark">{{ $patient->queue_number }}</span>
                                    </td>
                                    <td>
                                        <small>{{ $patient->joined_queue_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $patient->patient->full_name }}</strong><br>
                                        <small class="text-muted">{{ $patient->patient->card_number }}</small>
                                    </td>
                                    <td>
                                        @if($patient->patient->gender === 'Male')
                                            <span class="badge bg-primary">M</span>
                                        @else
                                            <span class="badge" style="background-color: #e91e63;">F</span>
                                        @endif
                                        {{ $patient->patient->age }}yrs
                                    </td>
                                    <td>{!! $patient->priority_badge !!}</td>
                                    <td>
                                        @if($patient->vitals)
                                            @if($patient->assessment)
                                                <span class="badge bg-success">
                                                    <i class="fa fa-check"></i> Completed
                                                </span>
                                            @else
                                                <span class="badge bg-info">
                                                    <i class="fa fa-heartbeat"></i> Vitals Captured
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fa fa-hourglass"></i> Pending Vitals
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">
                                            {{ $patient->wait_time_formatted }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if(!$patient->vitals)
                                                <!-- No vitals yet - Capture Vitals -->
                                                <a href="{{ route('admin.triage.capture-vitals', $patient->id) }}" 
                                                   class="btn btn-sm btn-primary" 
                                                   title="Capture Vitals">
                                                    <i class="fa fa-heartbeat"></i> Capture Vitals
                                                </a>
                                            @elseif(!$patient->assessment)
                                                <!-- Vitals captured but no assessment - Resume/Start Assessment -->
                                                <a href="{{ route('admin.triage.assessment', $patient->id) }}" 
                                                   class="btn btn-sm btn-success" 
                                                   title="Complete Assessment">
                                                    <i class="fa fa-clipboard-check"></i> Complete Assessment
                                                </a>
                                                <!-- Option to recapture vitals if needed -->
                                                <a href="{{ route('admin.triage.capture-vitals', $patient->id) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Recapture Vitals">
                                                    <i class="fa fa-redo"></i>
                                                </a>
                                            @else
                                                <!-- Both vitals and assessment completed -->
                                                <span class="badge bg-success">
                                                    <i class="fa fa-check-circle"></i> Ready for Forward
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No patients in triage queue</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.table-danger {
    background-color: #f8d7da !important;
}

.table-warning {
    background-color: #fff3cd !important;
}
</style>
@endpush

@push('js')
<script>
// Auto-refresh every 2 minutes
setTimeout(function() {
    location.reload();
}, 120000);
</script>
@endpush

@endsection