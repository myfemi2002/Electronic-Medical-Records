@extends('admin.admin_master')
@section('title', 'Departments Management')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="page-title">Departments</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item active">Departments</li>
                </ul>
            </div>
            <div class="col-sm-6 text-end">
                <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add Department
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-2 col-sm-6">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['total'] }}</h3>
                    <p class="text-muted mb-0">Total</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-success">{{ $stats['active'] }}</h3>
                    <p class="text-muted mb-0">Active</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-primary">{{ $stats['medical'] }}</h3>
                    <p class="text-muted mb-0">Medical</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-info">{{ $stats['support'] }}</h3>
                    <p class="text-muted mb-0">Support</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-secondary">{{ $stats['administrative'] }}</h3>
                    <p class="text-muted mb-0">Admin</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-6">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-1 text-warning">{{ $stats['can_receive_triage'] }}</h3>
                    <p class="text-muted mb-0">Triage</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Departments Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Departments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Department Name</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Head</th>
                                    <th>Staff</th>
                                    <th>Triage</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($departments as $dept)
                                <tr>
                                    <td>{{ $dept->display_order }}</td>
                                    <td><span class="badge bg-dark">{{ $dept->code }}</span></td>
                                    <td>
                                        <strong>{{ $dept->name }}</strong>
                                        @if($dept->description)
                                        <br><small class="text-muted">{{ Str::limit($dept->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>{!! $dept->type_badge !!}</td>
                                    <td>{{ $dept->location ?? '-' }}</td>
                                    <td>
                                        @if($dept->headOfDepartment)
                                            <span class="text-primary">
                                                <i class="fa fa-user"></i> {{ $dept->headOfDepartment->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">Not Assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $dept->staff_count }} <i class="fa fa-users"></i>
                                        </span>
                                    </td>
                                    <td>
                                        @if($dept->can_receive_triage_patients)
                                            <span class="badge bg-success">
                                                <i class="fa fa-check"></i> Yes
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fa fa-times"></i> No
                                            </span>
                                        @endif
                                    </td>
                                    <td>{!! $dept->status_badge !!}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.departments.show', $dept->id) }}" 
                                               class="btn btn-sm btn-info" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.departments.edit', $dept->id) }}" 
                                               class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.departments.toggle-status', $dept->id) }}" 
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-warning" 
                                                        title="Toggle Status">
                                                    <i class="fa fa-power-off"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.departments.delete', $dept->id) }}" 
                                                  method="GET" 
                                                  onsubmit="return confirm('Are you sure you want to delete this department?')" 
                                                  style="display: inline;">
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger" 
                                                        title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <i class="fa fa-building fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No departments found</p>
                                        <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
                                            <i class="fa fa-plus"></i> Add First Department
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
</div>

@endsection