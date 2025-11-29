{{-- resources/views/backend/records/patients/update-demographics.blade.php --}}
@extends('admin.admin_master')
@section('title', 'Update Demographics')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h3 class="page-title">Update Patient Demographics</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.records.patients.index') }}">Patient Registration</a></li>
                    <li class="breadcrumb-item active">Update Demographics</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Find Patient to Update</h5>
                </div>
                <div class="card-body">
                    <!-- Search Patient -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Search by Card Number</label>
                            <input type="text" class="form-control" id="searchCard" placeholder="Enter card number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Search by Name</label>
                            <input type="text" class="form-control" id="searchName" placeholder="Enter patient name">
                        </div>
                    </div>

                    <!-- Search Results -->
                    <div id="searchResults" style="display: none;">
                        <h6 class="mb-3">Search Results</h6>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Card Number</th>
                                        <th>Full Name</th>
                                        <th>Gender</th>
                                        <th>Phone</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="resultsBody">
                                    <!-- Results populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All Patients List -->
    <div class="row mt-4">
        <div class="col-sm-12">
            <div class="stats-card">
                <h5 class="mb-3 table-heading">All Patients</h5>
                
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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $patients = \App\Models\Patient::latest()->paginate(15);
                            @endphp
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
                                <td>
                                    <a href="{{ route('admin.records.patients.edit', $patient->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil-square me-1"></i> Update
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 empty-state">
                                    <i class="bi bi-inbox empty-icon"></i>
                                    <p class="empty-text">No patients registered yet</p>
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
    const patients = @json(\App\Models\Patient::all());
    
    // Search by card number
    document.getElementById('searchCard').addEventListener('input', function(e) {
        const searchValue = e.target.value.toLowerCase();
        filterPatients(searchValue, 'card');
    });
    
    // Search by name
    document.getElementById('searchName').addEventListener('input', function(e) {
        const searchValue = e.target.value.toLowerCase();
        filterPatients(searchValue, 'name');
    });
    
    function filterPatients(searchValue, type) {
        if (searchValue.length < 2) {
            document.getElementById('searchResults').style.display = 'none';
            return;
        }
        
        const results = patients.filter(patient => {
            if (type === 'card') {
                return patient.card_number.toLowerCase().includes(searchValue);
            } else {
                const fullName = `${patient.patient_firstname} ${patient.patient_lastname}`.toLowerCase();
                return fullName.includes(searchValue);
            }
        });
        
        displaySearchResults(results);
    }
    
    function displaySearchResults(results) {
        const resultsBody = document.getElementById('resultsBody');
        const resultsSection = document.getElementById('searchResults');
        
        if (results.length === 0) {
            resultsBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-3 empty-state">
                        <p class="empty-text mb-0">No patients found</p>
                    </td>
                </tr>
            `;
        } else {
            resultsBody.innerHTML = results.slice(0, 5).map(patient => `
                <tr>
                    <td><strong>${patient.card_number}</strong></td>
                    <td>${patient.patient_lastname} ${patient.patient_firstname}</td>
                    <td><span class="badge bg-${patient.patient_gender === 'Male' ? 'primary' : 'info'}">${patient.patient_gender}</span></td>
                    <td class="text-secondary">${patient.patient_phone}</td>
                    <td>
                        <a href="/admin/records/patients/edit/${patient.id}" class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil-square me-1"></i> Update
                        </a>
                    </td>
                </tr>
            `).join('');
        }
        
        resultsSection.style.display = 'block';
    }
</script>
@endpush