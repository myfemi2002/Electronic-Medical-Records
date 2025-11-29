{{-- resources/views/backend/records/patients/search.blade.php --}}
@extends('admin.admin_master')
@section('title', 'Search Patient')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h3 class="page-title">Search Patient</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.records.patients.index') }}">Patient Registration</a></li>
                    <li class="breadcrumb-item active">Search Patient</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Search for Patient Records</h5>
                </div>
                <div class="card-body">
                    <!-- Search Form -->
                    <form id="searchForm">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Card Number</label>
                                <input type="text" class="form-control" id="searchCardNumber" placeholder="Enter card number">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" id="searchFirstName" placeholder="Enter first name">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="searchLastName" placeholder="Enter last name">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="searchPhone" placeholder="Enter phone number">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Gender</label>
                                <select class="form-select" id="searchGender">
                                    <option value="">All Genders</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Patient Type</label>
                                <select class="form-select" id="searchType">
                                    <option value="">All Types</option>
                                    <option value="Private">Private</option>
                                    <option value="HMO">HMO</option>
                                    <option value="Retainership">Retainership</option>
                                    <option value="Pre-Medical">Pre-Medical</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" id="resetSearch">
                                <i class="bi bi-arrow-clockwise me-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results -->
    <div class="row mt-4" id="searchResults" style="display: none;">
        <div class="col-sm-12">
            <div class="stats-card">
                <h5 class="mb-3 table-heading">Search Results</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="resultsTable">
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
                        <tbody id="resultsBody">
                            <!-- Results will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Simulated patient data (replace with actual AJAX call to your backend)
    const patients = @json(\App\Models\Patient::all());
    
    document.getElementById('searchForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const cardNumber = document.getElementById('searchCardNumber').value.toLowerCase();
        const firstName = document.getElementById('searchFirstName').value.toLowerCase();
        const lastName = document.getElementById('searchLastName').value.toLowerCase();
        const phone = document.getElementById('searchPhone').value;
        const gender = document.getElementById('searchGender').value;
        const type = document.getElementById('searchType').value;
        
        const results = patients.filter(patient => {
            return (
                (!cardNumber || patient.card_number.toLowerCase().includes(cardNumber)) &&
                (!firstName || patient.patient_firstname.toLowerCase().includes(firstName)) &&
                (!lastName || patient.patient_lastname.toLowerCase().includes(lastName)) &&
                (!phone || patient.patient_phone.includes(phone)) &&
                (!gender || patient.patient_gender === gender) &&
                (!type || patient.patient_type === type)
            );
        });
        
        displayResults(results);
    });
    
    function displayResults(results) {
        const resultsBody = document.getElementById('resultsBody');
        const resultsSection = document.getElementById('searchResults');
        
        if (results.length === 0) {
            resultsBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-5 empty-state">
                        <i class="bi bi-inbox empty-icon"></i>
                        <p class="empty-text">No patients found matching your search criteria</p>
                    </td>
                </tr>
            `;
        } else {
            resultsBody.innerHTML = results.map((patient, index) => `
                <tr>
                    <td class="text-secondary">${index + 1}</td>
                    <td><strong>${patient.card_number}</strong></td>
                    <td>${patient.patient_lastname} ${patient.patient_firstname}</td>
                    <td>
                        <span class="badge bg-${patient.patient_gender === 'Male' ? 'primary' : 'info'}">
                            ${patient.patient_gender}
                        </span>
                    </td>
                    <td class="text-secondary">${patient.patient_phone}</td>
                    <td>
                        <span class="badge bg-${patient.patient_type === 'HMO' ? 'success' : patient.patient_type === 'Private' ? 'warning text-dark' : 'secondary'}">
                            ${patient.patient_type}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="/patients/show/${patient.id}" class="btn btn-sm btn-outline-info" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/patients/edit/${patient.id}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            `).join('');
        }
        
        resultsSection.style.display = 'block';
    }
    
    document.getElementById('resetSearch').addEventListener('click', function() {
        document.getElementById('searchForm').reset();
        document.getElementById('searchResults').style.display = 'none';
    });
</script>
@endpush