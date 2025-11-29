{{-- resources/views/backend/records/patients/open.blade.php --}}
@extends('admin.admin_master')
@section('title', 'Open Patient File')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h3 class="page-title">Open Patient File</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.records.patients.index') }}">Patient Registration</a></li>
                    <li class="breadcrumb-item active">Open Patient File</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <!-- Search and Open Patient File -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Search and Open Patient File</h5>
                </div>
                <div class="card-body">
                    <!-- Quick Search -->
                    <div class="row mb-4">
                        <div class="col-md-5 mb-3">
                            <label class="form-label">Search by Card Number</label>
                            <input type="text" class="form-control" id="quickSearchCard" placeholder="Enter patient card number">
                        </div>
                        <div class="col-md-2 mb-3 d-flex align-items-end">
                            <button type="button" class="btn btn-primary w-100" id="searchCardBtn">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label">Search by Phone Number</label>
                            <input type="text" class="form-control" id="quickSearchPhone" placeholder="Enter patient phone number">
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" id="searchPhoneBtn">
                                    <i class="bi bi-search me-1"></i> Search by Phone
                                </button>
                                <button type="button" class="btn btn-secondary" id="resetSearchBtn">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Search Results -->
                    <div id="searchResults" style="display: none;">
                        <div class="alert alert-success">
                            <strong><i class="bi bi-check-circle me-2"></i>Patient Found!</strong>
                            <div id="patientDetails" class="mt-2"></div>
                        </div>
                    </div>

                    <div id="noResults" style="display: none;">
                        <div class="alert alert-warning">
                            <strong><i class="bi bi-exclamation-triangle me-2"></i>No Patient Found</strong>
                            <p class="mb-0">No patient found with the search criteria. Please check and try again.</p>
                        </div>
                    </div>

                    <!-- Recent Patients -->
                    <h6 class="mb-3">Recent Patients (Quick Access)</h6>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="recentPatientsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Card Number</th>
                                    <th>Full Name</th>
                                    <th>Phone</th>
                                    <th>File Status</th>
                                    <th>Consultancy</th>
                                    <th>Last Visit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $recentPatients = \App\Models\Patient::latest()->take(10)->get();
                                @endphp
                                @forelse($recentPatients as $key => $patient)
                                <tr>
                                    <td class="text-secondary">{{ $key + 1 }}</td>
                                    <td><strong>{{ $patient->card_number }}</strong></td>
                                    <td>{{ $patient->patient_lastname }} {{ $patient->patient_firstname }}</td>
                                    <td class="text-secondary">{{ $patient->patient_phone }}</td>
                                    <td>
                                        @if($patient->isFileOpen() && $patient->file_opened_at && $patient->file_opened_at->isToday())
                                            <span class="badge bg-success">
                                                <i class="bi bi-folder-open me-1"></i>Open
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-folder me-1"></i>Closed
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($patient->hasActiveConsultancy())
                                            @php
                                                $activeConsultancy = $patient->getActiveConsultancy();
                                                $expiryDate = $activeConsultancy ? $activeConsultancy->consultancy_expiry_date->format('M d, Y') : 'N/A';
                                            @endphp
                                            <span class="badge bg-success" title="Expires: {{ $expiryDate }}">
                                                <i class="bi bi-check-circle me-1"></i>
                                                {{ $patient->consultancyDaysRemaining() }}d left
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-exclamation-circle me-1"></i>Payment Required
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-secondary">{{ $patient->created_at->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ route('admin.records.patients.open-file', $patient->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-folder-open me-1"></i> Open File
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 empty-state">
                                        <i class="bi bi-inbox empty-icon"></i>
                                        <p class="empty-text">No recent patients</p>
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

    
    <!-- Currently Open Files -->
    @if($openFiles->count() > 0)
    <div class="row mb-4 mt-4">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0 text-white">
                        <i class="bi bi-folder-open me-2"></i>Currently Open Files ({{ $openFiles->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Card Number</th>
                                    <th>Patient Name</th>
                                    <th>Phone</th>
                                    <th>Consultancy Status</th>
                                    <th>Opened At</th>
                                    <th>Opened By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($openFiles as $key => $file)
                                <tr>
                                    <td class="text-secondary">{{ $key + 1 }}</td>
                                    <td><strong>{{ $file->card_number }}</strong></td>
                                    <td>{{ $file->patient_lastname }} {{ $file->patient_firstname }}</td>
                                    <td class="text-secondary">{{ $file->patient_phone }}</td>
                                    <td>
                                        @if($file->hasActiveConsultancy())
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Valid ({{ $file->consultancyDaysRemaining() }}d left)
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>Expired
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-secondary">{{ $file->file_opened_at->format('h:i A') }}</td>
                                    <td class="text-secondary">
                                        @if($file->openedByUser)
                                            {{ $file->openedByUser->name }}
                                        @else
                                            System
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.records.patients.show', $file->id) }}" class="btn btn-sm btn-outline-info" title="View File">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.records.patients.close-file', $file->id) }}" class="btn btn-sm btn-outline-danger" title="Close File" onclick="return confirm('Close this patient file?')">
                                                <i class="bi bi-folder-x"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Note:</strong> All open files will automatically close at 11:59 PM today.
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


</div>

@endsection

@push('scripts')
<script>
    const patients = @json(\App\Models\Patient::all());
    
    // Search function for card number
    function searchByCardNumber() {
        const searchValue = document.getElementById('quickSearchCard').value.toUpperCase().trim();
        
        // Hide previous results
        document.getElementById('searchResults').style.display = 'none';
        document.getElementById('noResults').style.display = 'none';
        
        if (searchValue.length < 3) {
            alert('Please enter at least 3 characters');
            return;
        }
        
        const patient = patients.find(p => p.card_number.toUpperCase() === searchValue);
        
        if (patient) {
            displayPatientResult(patient);
        } else {
            document.getElementById('noResults').style.display = 'block';
        }
    }
    
    // Search function for phone number
    function searchByPhone() {
        const searchValue = document.getElementById('quickSearchPhone').value.trim();
        
        // Hide previous results
        document.getElementById('searchResults').style.display = 'none';
        document.getElementById('noResults').style.display = 'none';
        
        if (searchValue.length < 4) {
            alert('Please enter at least 4 digits');
            return;
        }
        
        const patient = patients.find(p => p.patient_phone.includes(searchValue));
        
        if (patient) {
            displayPatientResult(patient);
        } else {
            document.getElementById('noResults').style.display = 'block';
        }
    }
    
    // Display patient result
    function displayPatientResult(patient) {
        const fileStatus = patient.file_status === 'open' ? 
            '<span class="badge bg-success"><i class="bi bi-folder-open me-1"></i>Open</span>' : 
            '<span class="badge bg-secondary"><i class="bi bi-folder me-1"></i>Closed</span>';
        
        // Check consultancy status using new field
        const hasConsultancy = patient.has_active_consultancy;
        const consultancyStatus = hasConsultancy ?
            '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Active Consultancy</span>' :
            '<span class="badge bg-danger"><i class="bi bi-exclamation-circle me-1"></i>Payment Required</span>';
        
        const patientDetails = `
            <div class="card mt-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Card Number:</strong> ${patient.card_number}</p>
                            <p class="mb-2"><strong>Full Name:</strong> ${patient.patient_firstname} ${patient.patient_lastname}</p>
                            <p class="mb-2"><strong>Gender:</strong> ${patient.patient_gender}</p>
                            <p class="mb-2"><strong>Phone:</strong> ${patient.patient_phone}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Patient Type:</strong> ${patient.patient_type}</p>
                            <p class="mb-2"><strong>File Status:</strong> ${fileStatus}</p>
                            <p class="mb-2"><strong>Consultancy:</strong> ${consultancyStatus}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="/patients/open-file/${patient.id}" class="btn btn-primary">
                            <i class="bi bi-folder-open me-1"></i> Open Patient File
                        </a>
                        <a href="/patients/show/${patient.id}" class="btn btn-outline-info">
                            <i class="bi bi-eye me-1"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('patientDetails').innerHTML = patientDetails;
        document.getElementById('searchResults').style.display = 'block';
    }
    
    // Reset search
    function resetSearch() {
        document.getElementById('quickSearchCard').value = '';
        document.getElementById('quickSearchPhone').value = '';
        document.getElementById('searchResults').style.display = 'none';
        document.getElementById('noResults').style.display = 'none';
    }
    
    // Button click handlers
    document.getElementById('searchCardBtn').addEventListener('click', searchByCardNumber);
    document.getElementById('searchPhoneBtn').addEventListener('click', searchByPhone);
    document.getElementById('resetSearchBtn').addEventListener('click', resetSearch);
    
    // Allow search on Enter key press for card number
    document.getElementById('quickSearchCard').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchByCardNumber();
        }
    });
    
    // Allow search on Enter key press for phone number
    document.getElementById('quickSearchPhone').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchByPhone();
        }
    });
</script>
@endpush