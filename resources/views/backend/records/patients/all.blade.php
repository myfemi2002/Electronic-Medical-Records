{{-- resources/views/backend/records/patients/all.blade.php --}}
@extends('admin.admin_master')
@section('title', 'All Patients')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h3 class="page-title">All Patients</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.records.patients.index') }}">Patient Registration</a></li>
                    <li class="breadcrumb-item active">All Patients</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="row">
        <div class="col-sm-12">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 table-heading">Patient Records</h5>
                    <a href="{{ route('admin.records.patients.create') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus-fill me-2"></i> Register New Patient
                    </a>
                </div>

                <!-- Search and Filter -->
                <div class="row mb-3">
                    <div class="col-md-4 mb-2">
                        <input type="text" class="form-control" id="searchPatient" placeholder="Search by name, card number, or phone...">
                    </div>
                    <div class="col-md-3 mb-2">
                        <select class="form-select" id="filterGender">
                            <option value="">All Genders</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select class="form-select" id="filterType">
                            <option value="">All Patient Types</option>
                            <option value="Private">Private</option>
                            <option value="HMO">HMO</option>
                            <option value="Retainership">Retainership</option>
                            <option value="Pre-Medical">Pre-Medical</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="button" class="btn btn-secondary w-100" id="resetFilter">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Patients Table -->
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Card Number</th>
                                <th>Full Name</th>
                                <th>Gender</th>
                                <th>Phone</th>
                                <th>Patient Type</th>
                                <th>Registered Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patients as $key => $patient)
                            <tr>
                                <td class="text-secondary">{{ $patients->firstItem() + $key }}</td>
                                <td><strong>{{ $patient->card_number }}</strong></td>
                                <td>{{ $patient->patient_lastname }} {{ $patient->patient_firstname }}</td>
                                <td>
                                    @if($patient->patient_gender == 'Male')
                                        <span class="badge bg-primary">Male</span>
                                    @else
                                        <span class="badge bg-info">Female</span>
                                    @endif
                                </td>
                                <td class="text-secondary">{{ $patient->patient_phone }}</td>
                                <td>
                                    @if($patient->patient_type == 'HMO')
                                        <span class="badge bg-success">{{ $patient->patient_type }}</span>
                                    @elseif($patient->patient_type == 'Private')
                                        <span class="badge bg-warning text-dark">{{ $patient->patient_type }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $patient->patient_type }}</span>
                                    @endif
                                </td>
                                <td class="text-secondary">{{ $patient->created_at->format('d M, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.records.patients.edit', $patient->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="{{ route('admin.records.patients.show', $patient->id) }}" class="btn btn-sm btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.records.patients.delete', $patient->id) }}" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this patient?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 empty-state">
                                    <i class="bi bi-inbox empty-icon"></i>
                                    <p class="empty-text">No patients registered yet</p>
                                    <a href="{{ route('admin.records.patients.create') }}" class="btn btn-primary">
                                        <i class="bi bi-person-plus-fill me-2"></i> Register First Patient
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $patients->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchPatient').addEventListener('keyup', function() {
        let searchValue = this.value.toLowerCase();
        let tableRows = document.querySelectorAll('tbody tr');
        
        tableRows.forEach(row => {
            let text = row.textContent.toLowerCase();
            if (text.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Reset filter
    document.getElementById('resetFilter').addEventListener('click', function() {
        document.getElementById('searchPatient').value = '';
        document.getElementById('filterGender').value = '';
        document.getElementById('filterType').value = '';
        document.querySelectorAll('tbody tr').forEach(row => row.style.display = '');
    });
</script>
@endpush