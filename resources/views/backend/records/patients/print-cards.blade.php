{{-- resources/views/backend/records/patients/print-cards.blade.php --}}
@extends('admin.admin_master')
@section('title', 'Print Patient Cards')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h3 class="page-title">Print Patient Cards</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.records.patients.index') }}">Patient Registration</a></li>
                    <li class="breadcrumb-item active">Print Patient Cards</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <!-- Search Patient -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Find Patient to Print Card</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Search by Card Number</label>
                            <input type="text" class="form-control" id="searchCard" placeholder="Enter card number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Search by Patient Name</label>
                            <input type="text" class="form-control" id="searchName" placeholder="Enter patient name">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Patients List -->
    <div class="row mt-4">
        <div class="col-sm-12">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 table-heading">Select Patient(s) to Print Cards</h5>
                    <button type="button" class="btn btn-primary" id="printSelectedBtn" disabled>
                        <i class="bi bi-printer me-1"></i> Print Selected Cards
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="patientsTable">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
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
                            @forelse($patients as $key => $patient)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input patient-checkbox" value="{{ $patient->id }}" data-name="{{ $patient->patient_firstname }} {{ $patient->patient_lastname }}">
                                </td>
                                <td class="text-secondary">{{ $key + 1 }}</td>
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
                                    <a href="{{ route('admin.records.patients.print-card', $patient->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="bi bi-printer"></i> Print
                                    </a>
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
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Select all checkbox functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.patient-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updatePrintButton();
    });
    
    // Individual checkbox change
    document.querySelectorAll('.patient-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updatePrintButton);
    });
    
    // Update print button state
    function updatePrintButton() {
        const selectedCount = document.querySelectorAll('.patient-checkbox:checked').length;
        const printBtn = document.getElementById('printSelectedBtn');
        
        if (selectedCount > 0) {
            printBtn.disabled = false;
            printBtn.innerHTML = `<i class="bi bi-printer me-1"></i> Print ${selectedCount} Card${selectedCount > 1 ? 's' : ''}`;
        } else {
            printBtn.disabled = true;
            printBtn.innerHTML = '<i class="bi bi-printer me-1"></i> Print Selected Cards';
        }
    }
    
    // Print selected cards
    document.getElementById('printSelectedBtn').addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.patient-checkbox:checked'))
            .map(cb => cb.value);
        
        if (selectedIds.length === 0) {
            alert('Please select at least one patient');
            return;
        }
        
        // Open print window for each selected patient
        selectedIds.forEach(id => {
            window.open(`/admin/records/patients/print-card/${id}`, '_blank');
        });
    });
    
    // Search functionality
    document.getElementById('searchCard').addEventListener('input', filterTable);
    document.getElementById('searchName').addEventListener('input', filterTable);
    
    function filterTable() {
        const cardValue = document.getElementById('searchCard').value.toLowerCase();
        const nameValue = document.getElementById('searchName').value.toLowerCase();
        const rows = document.querySelectorAll('#patientsTable tbody tr');
        
        rows.forEach(row => {
            const cardNumber = row.cells[2]?.textContent.toLowerCase() || '';
            const patientName = row.cells[3]?.textContent.toLowerCase() || '';
            
            const matchesCard = !cardValue || cardNumber.includes(cardValue);
            const matchesName = !nameValue || patientName.includes(nameValue);
            
            if (matchesCard && matchesName) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endpush