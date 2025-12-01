@extends('admin.admin_master')
@section('title', 'Triage Dashboard')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="page-title">Triage Module</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item active">Triage</li>
                </ul>
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

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            <a href="{{ route('admin.triage.waiting-list') }}" class="btn btn-lg btn-outline-primary w-100">
                                <i class="fa fa-list fa-2x mb-2"></i>
                                <p class="mb-0">Waiting List</p>
                                @if($stats['waiting_count'] > 0)
                                    <span class="badge bg-primary">{{ $stats['waiting_count'] }}</span>
                                @endif
                            </a>
                        </div>

                        <div class="col-md-3 text-center mb-3">
                            <a href="{{ route('admin.triage.reports') }}" class="btn btn-lg btn-outline-info w-100">
                                <i class="fa fa-chart-bar fa-2x mb-2"></i>
                                <p class="mb-0">Reports</p>
                            </a>
                        </div>

                        <div class="col-md-3 text-center mb-3">
                            <a href="{{ route('admin.patients.index') }}" class="btn btn-lg btn-outline-secondary w-100">
                                <i class="fa fa-users fa-2x mb-2"></i>
                                <p class="mb-0">Patients</p>
                            </a>
                        </div>

                        <div class="col-md-3 text-center mb-3">
                            <a href="{{ route('admin.departments.index') }}" class="btn btn-lg btn-outline-success w-100">
                                <i class="fa fa-building fa-2x mb-2"></i>
                                <p class="mb-0">Departments</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Critical Patients Alert -->
    @if($criticalPatients->count() > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">
                    <i class="fa fa-exclamation-triangle"></i> Critical Patients Alert!
                </h5>
                <p><strong>{{ $criticalPatients->count() }}</strong> critical patient(s) in queue requiring immediate attention:</p>
                <ul class="mb-0">
                    @foreach($criticalPatients as $patient)
                        <li>
                            <strong>{{ $patient->patient->full_name }}</strong> ({{ $patient->queue_number }}) - 
                            Wait Time: {{ $patient->wait_time_formatted }}
                            <a href="{{ route('admin.triage.capture-vitals', $patient->id) }}" class="btn btn-sm btn-light ms-2">
                                Attend Now
                            </a>
                        </li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Today's Statistics -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Today's Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Total Patients:</span>
                                <strong class="text-primary">{{ $stats['total_today'] }}</strong>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Completed:</span>
                                <strong class="text-success">{{ $stats['completed_count'] }}</strong>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="d-flex justify-content-between">
                                <span>In Progress:</span>
                                <strong class="text-info">{{ $stats['in_progress_count'] }}</strong>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Waiting:</span>
                                <strong class="text-warning">{{ $stats['waiting_count'] }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Priority Distribution</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-danger">Critical</span>
                            <strong>{{ $stats['critical_count'] }}</strong>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-danger" 
                                 style="width: {{ $stats['total_today'] > 0 ? ($stats['critical_count'] / $stats['total_today'] * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-warning">Moderate</span>
                            <strong>{{ $stats['moderate_count'] }}</strong>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-warning" 
                                 style="width: {{ $stats['total_today'] > 0 ? ($stats['moderate_count'] / $stats['total_today'] * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-success">Mild</span>
                            <strong>{{ $stats['mild_count'] }}</strong>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" 
                                 style="width: {{ $stats['total_today'] > 0 ? ($stats['mild_count'] / $stats['total_today'] * 100) : 0 }}%">
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
</style>
@endpush

@endsection