@extends('admin.admin_master')
@section('title', 'Triage Queue Management')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="page-title">Triage Queue Management</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.triage.index') }}">Triage</a></li>
                    <li class="breadcrumb-item active">Queue</li>
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
        <div class="col-md-2 col-sm-6">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $stats['total_today'] }}</h3>
                    <p class="text-info mb-0">Total Today</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-info">{{ $stats['waiting_count'] }}</h3>
                    <p class="text-info mb-0">Waiting</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-primary">{{ $stats['in_progress_count'] }}</h3>
                    <p class="text-info mb-0">In Progress</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-danger">{{ $stats['critical_count'] }}</h3>
                    <p class="text-info mb-0">Critical</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-success">{{ $stats['completed_count'] }}</h3>
                    <p class="text-info mb-0">Completed</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ round($stats['average_wait_time']) }}</h3>
                    <p class="text-info mb-0">Avg Wait (min)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs for Different Status Views -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#all" role="tab">
                                <i class="fa fa-list"></i> All ({{ $allPatients->count() }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#waiting" role="tab">
                                <i class="fa fa-hourglass"></i> Waiting ({{ $waitingPatients->count() }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#in-progress" role="tab">
                                <i class="fa fa-spinner"></i> In Progress ({{ $inProgressPatients->count() }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#completed" role="tab">
                                <i class="fa fa-check-circle"></i> Completed ({{ $completedPatients->count() }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#forwarded" role="tab">
                                <i class="fa fa-arrow-right"></i> Forwarded ({{ $forwardedPatients->count() }})
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- ALL PATIENTS TAB -->
                        <div class="tab-pane fade show active" id="all" role="tabpanel">
                            @include('backend.triage.partials.patient-table', ['patients' => $allPatients, 'showAll' => true])
                        </div>

                        <!-- WAITING TAB -->
                        <div class="tab-pane fade" id="waiting" role="tabpanel">
                            @include('backend.triage.partials.patient-table', ['patients' => $waitingPatients, 'showAll' => false])
                        </div>

                        <!-- IN PROGRESS TAB -->
                        <div class="tab-pane fade" id="in-progress" role="tabpanel">
                            @include('backend.triage.partials.patient-table', ['patients' => $inProgressPatients, 'showAll' => false])
                        </div>

                        <!-- COMPLETED TAB -->
                        <div class="tab-pane fade" id="completed" role="tabpanel">
                            @include('backend.triage.partials.patient-table', ['patients' => $completedPatients, 'showAll' => false])
                        </div>

                        <!-- FORWARDED TAB -->
                        <div class="tab-pane fade" id="forwarded" role="tabpanel">
                            @include('backend.triage.partials.patient-table', ['patients' => $forwardedPatients, 'showAll' => false])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
.nav-tabs .nav-link.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.nav-tabs .nav-link {
    color: #495057;
}

.nav-tabs .nav-link:hover {
    border-color: #dee2e6 #dee2e6 #fff;
}

.table-danger {
    background-color: #f8d7da !important;
}

.table-warning {
    background-color: #fff3cd !important;
}

.table-success {
    background-color: #d1e7dd !important;
}
</style>
@endpush

@push('js')
<script>
// Auto-refresh every 2 minutes
setTimeout(function() {
    location.reload();
}, 120000);

// Store active tab in localStorage
document.querySelectorAll('.nav-tabs a').forEach(function(tab) {
    tab.addEventListener('click', function() {
        localStorage.setItem('triageActiveTab', this.getAttribute('href'));
    });
});

// Restore active tab on page load
document.addEventListener('DOMContentLoaded', function() {
    const activeTab = localStorage.getItem('triageActiveTab');
    if (activeTab) {
        const tabTrigger = document.querySelector('.nav-tabs a[href="' + activeTab + '"]');
        if (tabTrigger) {
            const tab = new bootstrap.Tab(tabTrigger);
            tab.show();
        }
    }
});
</script>
@endpush

@endsection