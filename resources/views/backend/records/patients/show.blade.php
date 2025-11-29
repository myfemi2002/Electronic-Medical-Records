{{-- resources/views/backend/records/patients/show.blade.php --}}
@extends('admin.admin_master')
@section('title', 'Patient Details')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h3 class="page-title">Patient Details</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.records.patients.index') }}">Patient Registration</a></li>
                    <li class="breadcrumb-item active">Patient Details</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <!-- Alert Messages -->
    @if(session('message'))
    <div class="alert alert-{{ session('alert-type', 'info') }} alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- File & Consultancy Status Bar -->
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card border-start border-5 {{ $patient->isFileOpen() ? 'border-success' : 'border-secondary' }}">
                <div class="card-body py-2">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-folder{{ $patient->isFileOpen() ? '-open' : '' }} fs-4 text-{{ $patient->isFileOpen() ? 'success' : 'secondary' }}"></i>
                                <div>
                                    <small class="text-muted d-block">File Status</small>
                                    <strong class="text-{{ $patient->isFileOpen() ? 'success' : 'secondary' }}">
                                        {{ $patient->isFileOpen() ? 'OPEN' : 'CLOSED' }}
                                    </strong>
                                    @if($patient->isFileOpen() && $patient->file_opened_at)
                                        <small class="d-block text-muted">Opened: {{ $patient->file_opened_at->format('h:i A') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-receipt fs-4 text-{{ $consultancyStatus['status'] === 'active' ? 'success' : 'warning' }}"></i>
                                <div>
                                    <small class="text-muted d-block">Consultancy Status</small>
                                    @if($consultancyStatus['status'] === 'active')
                                        <strong class="text-success">ACTIVE</strong>
                                        <small class="d-block text-muted">
                                            Expires: {{ $consultancyStatus['expires_at'] }} ({{ $consultancyStatus['days_remaining'] }}d left)
                                        </small>
                                    @elseif($consultancyStatus['status'] === 'expired')
                                        <strong class="text-danger">EXPIRED</strong>
                                        <small class="d-block text-muted">Last expired: {{ $consultancyStatus['expired_on'] }}</small>
                                    @else
                                        <strong class="text-secondary">NEVER PAID</strong>
                                        <small class="d-block text-muted">No consultancy on record</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5 text-end">
                            <div class="btn-group" role="group">
                                @if($patient->needsConsultancyPayment())
                                    <a href="{{ route('admin.records.patients.verify-consultancy', $patient->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-receipt me-1"></i> Verify Payment
                                    </a>
                                @endif
                                
                                @if(!$patient->isFileOpen() && $patient->hasActiveConsultancy())
                                    <a href="{{ route('admin.records.patients.open-file', $patient->id) }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-folder-open me-1"></i> Open File
                                    </a>
                                @elseif($patient->isFileOpen())
                                    <a href="{{ route('admin.records.patients.close-file', $patient->id) }}" class="btn btn-sm btn-secondary" onclick="return confirm('Close this patient file?')">
                                        <i class="bi bi-folder-x me-1"></i> Close File
                                    </a>
                                @endif
                                
                                <a href="{{ route('admin.records.patients.consultancy-history', $patient->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-clock-history me-1"></i> Payment History
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.records.patients.edit', $patient->id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil-square me-1"></i> Edit Patient
                </a>
                <a href="{{ route('admin.records.patients.print-card', $patient->id) }}" class="btn btn-info" target="_blank">
                    <i class="bi bi-credit-card-2-front me-1"></i> Print ID Card
                </a>
                <a href="{{ route('admin.records.patients.all') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Patient Photo & Basic Info -->
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body text-center">
                    @if($patient->image)
                        <img src="{{ asset($patient->image) }}" alt="Patient Photo" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid var(--primary-color);">
                    @else
                        <div class="rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 150px; height: 150px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
                            <i class="bi bi-person-fill" style="font-size: 4rem; color: white;"></i>
                        </div>
                    @endif
                    
                    <h4 class="mb-1">{{ $patient->patient_firstname }} {{ $patient->patient_lastname }}</h4>
                    <p class="text-muted mb-2">Card No: <strong>{{ $patient->card_number }}</strong></p>
                    
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        @if($patient->patient_gender == 'Male')
                            <span class="badge bg-primary">Male</span>
                        @else
                            <span class="badge bg-info">Female</span>
                        @endif
                        
                        @if($patient->patient_type == 'HMO')
                            <span class="badge bg-success">{{ $patient->patient_type }}</span>
                        @elseif($patient->patient_type == 'Private')
                            <span class="badge bg-warning text-dark">{{ $patient->patient_type }}</span>
                        @else
                            <span class="badge bg-secondary">{{ $patient->patient_type }}</span>
                        @endif
                    </div>
                    
                    <div class="text-start">
                        <div class="mb-2">
                            <i class="bi bi-telephone-fill text-primary me-2"></i>
                            <strong>Phone:</strong> {{ $patient->patient_phone }}
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-calendar-fill text-success me-2"></i>
                            <strong>Age:</strong> {{ $patient->age }} years
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-calendar-event-fill text-info me-2"></i>
                            <strong>DOB:</strong> {{ $patient->patient_dob->format('d M, Y') }}
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-clock-history text-warning me-2"></i>
                            <strong>Registered:</strong> {{ $patient->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consultancy Summary Card -->
            <div class="card mb-3">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="bi bi-receipt me-2"></i>Consultancy Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Total Payments</small>
                        <h4 class="mb-0">{{ $patient->total_consultancy_paid }}</h4>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Total Amount Paid</small>
                        <h5 class="mb-0 text-success">₦{{ number_format($patient->total_consultancy_amount, 2) }}</h5>
                    </div>
                    @if($consultancyStatus['status'] === 'active')
                    <div class="mb-3">
                        <small class="text-muted d-block">Current Receipt</small>
                        <h6 class="mb-0">{{ $consultancyStatus['receipt_number'] ?? 'N/A' }}</h6>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Days Remaining</small>
                        <h5 class="mb-0 text-success">{{ $consultancyStatus['days_remaining'] }} days</h5>
                    </div>
                    @endif
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.records.patients.consultancy-history', $patient->id) }}" class="btn btn-sm btn-outline-warning w-100">
                            <i class="bi bi-list-ul me-1"></i> View Full History
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">File Status</small>
                        @if($patient->isFileOpen() && $patient->file_opened_at && $patient->file_opened_at->isToday())
                            <h6 class="mb-0 text-success">
                                <i class="bi bi-folder-open me-1"></i>Open Today
                            </h6>
                        @else
                            <h6 class="mb-0 text-secondary">
                                <i class="bi bi-folder me-1"></i>Closed
                            </h6>
                        @endif
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Last File Access</small>
                        <h6 class="mb-0">
                            @if($patient->file_opened_at)
                                {{ $patient->file_opened_at->format('d M, Y') }}
                            @else
                                Never
                            @endif
                        </h6>
                    </div>
                    @if($patient->openedByUser)
                    <div class="mb-0">
                        <small class="text-muted d-block">Opened By</small>
                        <h6 class="mb-0">{{ $patient->openedByUser->name }}</h6>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Patient Details -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="bi bi-person-fill me-2"></i>Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">First Name</label>
                            <p class="mb-0"><strong>{{ $patient->patient_firstname }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Last Name</label>
                            <p class="mb-0"><strong>{{ $patient->patient_lastname }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Card Number</label>
                            <p class="mb-0"><strong>{{ $patient->card_number }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Phone Number</label>
                            <p class="mb-0"><strong>{{ $patient->patient_phone }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Gender</label>
                            <p class="mb-0"><strong>{{ $patient->patient_gender }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Date of Birth</label>
                            <p class="mb-0"><strong>{{ $patient->patient_dob->format('d M, Y') }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Age</label>
                            <p class="mb-0"><strong>{{ $patient->age }} years</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Marital Status</label>
                            <p class="mb-0"><strong>{{ $patient->patient_status }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Religion</label>
                            <p class="mb-0"><strong>{{ $patient->patient_religion }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Occupation</label>
                            <p class="mb-0"><strong>{{ $patient->patient_occupation ?? 'Not Specified' }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Nationality</label>
                            <p class="mb-0"><strong>{{ $patient->patient_nationality ?? 'Not Specified' }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Patient Type</label>
                            <p class="mb-0"><strong>{{ $patient->patient_type }}</strong></p>
                        </div>
                        @if($patient->patient_type == 'HMO' && $patient->patient_hmo)
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted">HMO Provider</label>
                            <p class="mb-0"><strong>{{ $patient->patient_hmo }}</strong></p>
                        </div>
                        @endif
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted">Address</label>
                            <p class="mb-0"><strong>{{ $patient->patient_address ?? 'Not Specified' }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next of Kin Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="bi bi-people-fill me-2"></i>Next of Kin Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Name</label>
                            <p class="mb-0"><strong>{{ $patient->patient_kin_name ?? 'Not Specified' }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Relationship</label>
                            <p class="mb-0"><strong>{{ $patient->kin_relationship ?? 'Not Specified' }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Phone Number</label>
                            <p class="mb-0"><strong>{{ $patient->patient_kin_phone ?? 'Not Specified' }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Address</label>
                            <p class="mb-0"><strong>{{ $patient->patient_kin_address ?? 'Not Specified' }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="bi bi-info-circle-fill me-2"></i>Registration Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Registration Date</label>
                            <p class="mb-0"><strong>{{ $patient->created_at->format('d M, Y h:i A') }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Last Updated</label>
                            <p class="mb-0"><strong>{{ $patient->updated_at->format('d M, Y h:i A') }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Patient ID</label>
                            <p class="mb-0"><strong>#{{ str_pad($patient->id, 6, '0', STR_PAD_LEFT) }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Total Visits</label>
                            <p class="mb-0"><strong>N/A</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Consultancy Payments -->
    @if($consultancyHistory->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-receipt me-2"></i>Recent Consultancy Payments
                            </h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.records.patients.consultancy-history', $patient->id) }}" class="btn btn-sm btn-outline-warning">
                                View All Payments
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Receipt No.</th>
                                    <th>Payment Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Valid Period</th>
                                    <th>Status</th>
                                    <th>Verified By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($consultancyHistory->take(5) as $key => $payment)
                                <tr>
                                    <td class="text-secondary">{{ $key + 1 }}</td>
                                    <td><strong>{{ $payment->receipt_number }}</strong></td>
                                    <td class="text-secondary">{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td class="text-success"><strong>₦{{ number_format($payment->amount_paid, 2) }}</strong></td>
                                    <td><span class="badge bg-info">{{ $payment->payment_method }}</span></td>
                                    <td class="text-secondary small">
                                        {{ $payment->consultancy_start_date->format('M d') }} - 
                                        {{ $payment->consultancy_expiry_date->format('M d, Y') }}
                                    </td>
                                    <td>
                                        @if($payment->isActive())
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-x-circle me-1"></i>Expired
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-secondary small">
                                        {{ $payment->verifiedBy->name ?? 'System' }}
                                    </td>
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

    <!-- Medical History Section (Placeholder) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="bi bi-clipboard-pulse me-2"></i>Medical History & Visits</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Visit Date</th>
                                    <th>Department</th>
                                    <th>Complaint</th>
                                    <th>Diagnosis</th>
                                    <th>Doctor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7" class="text-center py-5 empty-state">
                                        <i class="bi bi-inbox empty-icon"></i>
                                        <p class="empty-text">No medical history recorded yet</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection