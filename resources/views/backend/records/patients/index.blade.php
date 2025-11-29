{{-- resources/views/backend/records/patients/index.blade.php --}}
@extends('admin.admin_master')
@section('title', 'Patient Registration')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h3 class="page-title">Patient Registration Module</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item">Records Module</li>
                    <li class="breadcrumb-item active">Patient Registration</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <!-- Quick Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stats-card">
                <h6><i class="bi bi-people me-1"></i> Total Patients</h6>
                <h3>{{ $totalPatients ?? 0 }}</h3>
                <span class="badge bg-primary">All registered</span>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stats-card">
                <h6><i class="bi bi-person-plus me-1"></i> New Today</h6>
                <h3>{{ $newToday ?? 0 }}</h3>
                <span class="badge bg-success">Registered today</span>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stats-card">
                <h6><i class="bi bi-shield-check me-1"></i> HMO Patients</h6>
                <h3>{{ $hmoPatients ?? 0 }}</h3>
                <span class="badge bg-info">Insurance</span>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stats-card">
                <h6><i class="bi bi-wallet2 me-1"></i> Private Patients</h6>
                <h3>{{ $privatePatients ?? 0 }}</h3>
                <span class="badge bg-warning text-dark">Cash</span>
            </div>
        </div>
    </div>

    <!-- Patient Management Actions -->
    <div class="section-header">
        <h4><i class="bi bi-grid-3x3-gap me-2"></i>Patient Management Actions</h4>
        <p>Select an action to manage patient records</p>
    </div>

    <div class="row g-3 g-lg-4 mb-4">
        <!-- Register Patient -->
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a href="{{ route('admin.records.patients.create') }}" class="dashboard-tile" tabindex="0">
                <div class="tile-icon">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <h5 class="tile-title">Register Patient</h5>
                <p class="tile-description">Add new patient</p>
            </a>
        </div>

        <!-- All Patients -->
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a href="{{ route('admin.records.patients.all') }}" class="dashboard-tile" tabindex="0">
                <div class="tile-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h5 class="tile-title">All Patients</h5>
                <p class="tile-description">View all records</p>
            </a>
        </div>

        <!-- Search Patient -->
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a href="{{ route('admin.records.patients.search') }}" class="dashboard-tile" tabindex="0">
                <div class="tile-icon">
                    <i class="bi bi-search"></i>
                </div>
                <h5 class="tile-title">Search Patient</h5>
                <p class="tile-description">Find patient records</p>
            </a>
        </div>

        <!-- Open Records -->
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a href="{{ route('admin.records.patients.open') }}" class="dashboard-tile" tabindex="0">
                <div class="tile-icon">
                    <i class="bi bi-folder-open"></i>
                </div>
                <h5 class="tile-title">Open Records</h5>
                <p class="tile-description">View patient files</p>
            </a>
        </div>

        <!-- Update Demographics -->
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a href="{{ route('admin.records.patients.update-demographics') }}" class="dashboard-tile" tabindex="0">
                <div class="tile-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <h5 class="tile-title">Update Demographics</h5>
                <p class="tile-description">Edit patient info</p>
            </a>
        </div>

        <!-- Visit History -->
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a href="{{ route('admin.records.patients.visit-history') }}" class="dashboard-tile" tabindex="0">
                <div class="tile-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <h5 class="tile-title">Visit History</h5>
                <p class="tile-description">Patient visits log</p>
            </a>
        </div>

        <!-- Print Patient Card -->
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a href="{{ route('admin.records.patients.print-cards') }}" class="dashboard-tile" tabindex="0">
                <div class="tile-icon">
                    <i class="bi bi-credit-card-2-front"></i>
                </div>
                <h5 class="tile-title">Print Patient Card</h5>
                <p class="tile-description">Generate ID cards</p>
            </a>
        </div>

        <!-- Patient Reports -->
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <a href="{{ route('admin.records.patients.reports') }}" class="dashboard-tile" tabindex="0">
                <div class="tile-icon">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                </div>
                <h5 class="tile-title">Patient Reports</h5>
                <p class="tile-description">Statistics & analytics</p>
            </a>
        </div>
    </div>

    <!-- Recent Patients -->
    <div class="section-header">
        <h4><i class="bi bi-clock-history me-2"></i>Recently Registered Patients</h4>
        <p>Last 10 patients registered in the system</p>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="stats-card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--border-color);">
                                <th style="color: var(--text-primary); font-weight: 600;">#</th>
                                <th style="color: var(--text-primary); font-weight: 600;">Card Number</th>
                                <th style="color: var(--text-primary); font-weight: 600;">Full Name</th>
                                <th style="color: var(--text-primary); font-weight: 600;">Gender</th>
                                <th style="color: var(--text-primary); font-weight: 600;">Phone</th>
                                <th style="color: var(--text-primary); font-weight: 600;">Patient Type</th>
                                <th style="color: var(--text-primary); font-weight: 600;">Registered</th>
                                <th style="color: var(--text-primary); font-weight: 600;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPatients as $key => $patient)
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td style="color: var(--text-secondary);">{{ $key + 1 }}</td>
                                <td style="color: var(--text-primary);"><strong>{{ $patient->card_number }}</strong></td>
                                <td style="color: var(--text-primary);">{{ $patient->patient_lastname }} {{ $patient->patient_firstname }}</td>
                                <td>
                                    @if($patient->patient_gender == 'Male')
                                        <span class="badge bg-primary">Male</span>
                                    @else
                                        <span class="badge bg-info">Female</span>
                                    @endif
                                </td>
                                <td style="color: var(--text-secondary);">{{ $patient->patient_phone }}</td>
                                <td>
                                    @if($patient->patient_type == 'HMO')
                                        <span class="badge bg-success">{{ $patient->patient_type }}</span>
                                    @elseif($patient->patient_type == 'Private')
                                        <span class="badge bg-warning text-dark">{{ $patient->patient_type }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $patient->patient_type }}</span>
                                    @endif
                                </td>
                                <td style="color: var(--text-secondary);">{{ $patient->created_at->diffForHumans() }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.records.patients.edit', $patient->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="{{ route('admin.records.patients.show', $patient->id) }}" class="btn btn-sm btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5" style="border: none;">
                                    <i class="bi bi-inbox" style="font-size: 3rem; color: var(--text-muted);"></i>
                                    <p style="color: var(--text-muted); margin-top: 1rem; margin-bottom: 1rem;">No patients registered yet</p>
                                    <a href="{{ route('admin.records.patients.create') }}" class="btn btn-primary">
                                        <i class="bi bi-person-plus-fill me-2"></i> Register First Patient
                                    </a>
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

@endsection