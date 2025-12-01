@extends('admin.admin_master')
@section('title', 'Department Details - ' . $department->name)
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="page-title">{{ $department->name }}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.departments.index') }}">Departments</a></li>
                    <li class="breadcrumb-item active">{{ $department->name }}</li>
                </ul>
            </div>
            <div class="col-sm-6 text-end">
                <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-primary">
                    <i class="fa fa-edit"></i> Edit Department
                </a>
            </div>
        </div>
    </div>

    <!-- Department Info Card -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-building"></i> Department Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Code:</strong>
                        </div>
                        <div class="col-md-9">
                            <span class="badge bg-dark">{{ $department->code }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Type:</strong>
                        </div>
                        <div class="col-md-9">
                            {!! $department->type_badge !!}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-md-9">
                            {!! $department->status_badge !!}
                        </div>
                    </div>

                    @if($department->description)
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Description:</strong>
                        </div>
                        <div class="col-md-9">
                            {{ $department->description }}
                        </div>
                    </div>
                    @endif

                    @if($department->location)
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Location:</strong>
                        </div>
                        <div class="col-md-9">
                            <i class="fa fa-map-marker-alt"></i> {{ $department->location }}
                        </div>
                    </div>
                    @endif

                    @if($department->phone)
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Phone:</strong>
                        </div>
                        <div class="col-md-9">
                            <i class="fa fa-phone"></i> {{ $department->phone }}
                        </div>
                    </div>
                    @endif

                    @if($department->email)
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-md-9">
                            <i class="fa fa-envelope"></i> {{ $department->email }}
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Head of Department:</strong>
                        </div>
                        <div class="col-md-9">
                            @if($department->headOfDepartment)
                                <i class="fa fa-user text-primary"></i> {{ $department->headOfDepartment->name }}
                                @if($department->headOfDepartment->staff_type)
                                    <span class="badge bg-info">{{ ucfirst($department->headOfDepartment->staff_type) }}</span>
                                @endif
                            @else
                                <span class="text-muted">Not Assigned</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Triage Patients:</strong>
                        </div>
                        <div class="col-md-9">
                            @if($department->can_receive_triage_patients)
                                <span class="badge bg-success"><i class="fa fa-check"></i> Can Receive</span>
                            @else
                                <span class="badge bg-secondary"><i class="fa fa-times"></i> Cannot Receive</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Appointment Required:</strong>
                        </div>
                        <div class="col-md-9">
                            @if($department->requires_appointment)
                                <span class="badge bg-warning">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Statistics Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-chart-bar"></i> Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Total Staff</span>
                            <strong class="text-primary">{{ $stats['total_staff'] }}</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Total Forwards</span>
                            <strong class="text-info">{{ $stats['total_forwards'] }}</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-info" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Today</span>
                            <strong class="text-success">{{ $stats['forwards_today'] }}</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success" style="width: {{ $stats['total_forwards'] > 0 ? ($stats['forwards_today'] / $stats['total_forwards'] * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>This Week</span>
                            <strong class="text-warning">{{ $stats['forwards_this_week'] }}</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-warning" style="width: {{ $stats['total_forwards'] > 0 ? ($stats['forwards_this_week'] / $stats['total_forwards'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff List -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-users"></i> Department Staff ({{ $stats['total_staff'] }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($department->staff->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Staff ID</th>
                                    <th>Name</th>
                                    <th>Staff Type</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($department->staff as $staff)
                                <tr>
                                    <td>
                                        @if($staff->staff_id)
                                            <span class="badge bg-dark">{{ $staff->staff_id }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $staff->name }}</td>
                                    <td>
                                        @if($staff->staff_type)
                                            <span class="badge bg-info">{{ ucfirst($staff->staff_type) }}</span>
                                        @else
                                            <span class="text-muted">Not Set</span>
                                        @endif
                                    </td>
                                    <td>{{ $staff->email }}</td>
                                    <td>
                                        @if($staff->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fa fa-users fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No staff assigned to this department yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Forwards -->
    @if($department->can_receive_triage_patients && $recentForwards->count() > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-arrow-right"></i> Recent Triage Forwards
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Card Number</th>
                                    <th>Priority</th>
                                    <th>Assessed By</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentForwards as $forward)
                                <tr>
                                    <td>{{ $forward->forwarded_at->format('M d, Y H:i') }}</td>
                                    <td>{{ $forward->patient->full_name }}</td>
                                    <td><span class="badge bg-primary">{{ $forward->patient->card_number }}</span></td>
                                    <td>{!! $forward->priority_badge !!}</td>
                                    <td>{{ $forward->assessedBy->name ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($forward->forwarding_reason, 50) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection