@extends('admin.admin_master')
@section('title', 'Triage Reports')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="page-title">Triage Reports</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.triage.index') }}">Triage</a></li>
                    <li class="breadcrumb-item active">Reports</li>
                </ul>
            </div>
            <div class="col-sm-6 text-end">
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fa fa-print"></i> Print Report
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
                            <h4 class="mb-0">{{ $stats['total_today'] }}</h4>
                            <p class="text-muted mb-0">Total Today</p>
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
                            <i class="fa fa-check-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-0">{{ $stats['completed_count'] }}</h4>
                            <p class="text-muted mb-0">Completed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info">
                            <i class="fa fa-hourglass-half"></i>
                        </div>
                        <div class="ms-3">
                            <h4 class="mb-0">{{ $stats['in_progress_count'] }}</h4>
                            <p class="text-muted mb-0">In Progress</p>
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

    <!-- Priority Distribution -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Priority Distribution</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <h3 class="text-danger mb-0">{{ $priorityDistribution['critical'] }}</h3>
                            <p class="text-muted mb-0">Critical</p>
                        </div>
                        <div class="col-4">
                            <h3 class="text-warning mb-0">{{ $priorityDistribution['moderate'] }}</h3>
                            <p class="text-muted mb-0">Moderate</p>
                        </div>
                        <div class="col-4">
                            <h3 class="text-success mb-0">{{ $priorityDistribution['mild'] }}</h3>
                            <p class="text-muted mb-0">Mild</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-danger">Critical</span>
                            <strong>{{ $stats['total_today'] > 0 ? round(($priorityDistribution['critical'] / $stats['total_today']) * 100, 1) : 0 }}%</strong>
                        </div>
                        <div class="progress" style="height: 15px;">
                            <div class="progress-bar bg-danger" 
                                 style="width: {{ $stats['total_today'] > 0 ? ($priorityDistribution['critical'] / $stats['total_today'] * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-warning">Moderate</span>
                            <strong>{{ $stats['total_today'] > 0 ? round(($priorityDistribution['moderate'] / $stats['total_today']) * 100, 1) : 0 }}%</strong>
                        </div>
                        <div class="progress" style="height: 15px;">
                            <div class="progress-bar bg-warning" 
                                 style="width: {{ $stats['total_today'] > 0 ? ($priorityDistribution['moderate'] / $stats['total_today'] * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-success">Mild</span>
                            <strong>{{ $stats['total_today'] > 0 ? round(($priorityDistribution['mild'] / $stats['total_today']) * 100, 1) : 0 }}%</strong>
                        </div>
                        <div class="progress" style="height: 15px;">
                            <div class="progress-bar bg-success" 
                                 style="width: {{ $stats['total_today'] > 0 ? ($priorityDistribution['mild'] / $stats['total_today'] * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Status Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fa fa-hourglass text-info"></i> Waiting</span>
                            <strong>{{ $stats['waiting_count'] }}</strong>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-info" 
                                 style="width: {{ $stats['total_today'] > 0 ? ($stats['waiting_count'] / $stats['total_today'] * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fa fa-spinner text-primary"></i> In Progress</span>
                            <strong>{{ $stats['in_progress_count'] }}</strong>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-primary" 
                                 style="width: {{ $stats['total_today'] > 0 ? ($stats['in_progress_count'] / $stats['total_today'] * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fa fa-check text-success"></i> Completed</span>
                            <strong>{{ $stats['completed_count'] }}</strong>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" 
                                 style="width: {{ $stats['total_today'] > 0 ? ($stats['completed_count'] / $stats['total_today'] * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-muted mb-1">Completion Rate</p>
                        <h3 class="text-success mb-0">
                            {{ $stats['total_today'] > 0 ? round(($stats['completed_count'] / $stats['total_today']) * 100, 1) : 0 }}%
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Queue Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Today's Triage Queue</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Queue #</th>
                                    <th>Time</th>
                                    <th>Card Number</th>
                                    <th>Patient Name</th>
                                    <th>Gender</th>
                                    <th>Age</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Wait Time</th>
                                    <th>Assigned To</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todayQueue as $item)
                                <tr>
                                    <td><span class="badge bg-dark">{{ $item->queue_number }}</span></td>
                                    <td>{{ $item->joined_queue_at->format('H:i') }}</td>
                                    <td>{{ $item->patient->card_number }}</td>
                                    <td>{{ $item->patient->full_name }}</td>
                                    <td>
                                        @if($item->patient->gender === 'Male')
                                            <span class="badge bg-primary">M</span>
                                        @else
                                            <span class="badge" style="background-color: #e91e63;">F</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->patient->age }}yrs</td>
                                    <td>{!! $item->priority_badge !!}</td>
                                    <td>{!! $item->status_badge !!}</td>
                                    <td>
                                        <span class="badge bg-warning">
                                            {{ $item->wait_time_formatted }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->assignedStaff)
                                            {{ $item->assignedStaff->name }}
                                            @if($item->assigned_staff_role)
                                                <br><small class="text-muted">({{ ucfirst($item->assigned_staff_role) }})</small>
                                            @endif
                                        @else
                                            <span class="text-muted">Not Assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No patients in triage queue today</p>
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

    <!-- Key Metrics -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Key Performance Indicators</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <h4 class="text-danger mb-1">{{ $stats['critical_count'] }}</h4>
                                <p class="text-muted mb-0">Critical Cases</p>
                                <small class="text-muted">
                                    {{ $stats['total_today'] > 0 ? round(($stats['critical_count'] / $stats['total_today']) * 100, 1) : 0 }}% of total
                                </small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <h4 class="text-warning mb-1">{{ $stats['moderate_count'] }}</h4>
                                <p class="text-muted mb-0">Moderate Cases</p>
                                <small class="text-muted">
                                    {{ $stats['total_today'] > 0 ? round(($stats['moderate_count'] / $stats['total_today']) * 100, 1) : 0 }}% of total
                                </small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <h4 class="text-success mb-1">{{ $stats['mild_count'] }}</h4>
                                <p class="text-muted mb-0">Mild Cases</p>
                                <small class="text-muted">
                                    {{ $stats['total_today'] > 0 ? round(($stats['mild_count'] / $stats['total_today']) * 100, 1) : 0 }}% of total
                                </small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <h4 class="text-primary mb-1">{{ round($stats['average_wait_time']) }}</h4>
                                <p class="text-muted mb-0">Avg Wait Time</p>
                                <small class="text-muted">minutes</small>
                            </div>
                        </div>
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

@media print {
    .page-header .btn,
    .breadcrumb,
    .sidebar,
    .header {
        display: none !important;
    }
}
</style>
@endpush

@endsection