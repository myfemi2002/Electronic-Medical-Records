{{-- resources/views/backend/records/patients/verify-consultancy.blade.php --}}
@extends('admin.admin_master')
@section('title', 'Verify Consultancy Payment')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h3 class="page-title">Verify Consultancy Payment</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.records.patients.index') }}">Patient Registration</a></li>
                    <li class="breadcrumb-item active">Verify Consultancy Payment</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="row">
        <!-- Patient Information -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0 text-white">
                        <i class="bi bi-person-circle me-2"></i>Patient Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Card Number</label>
                        <h6>{{ $patient->card_number }}</h6>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Full Name</label>
                        <h6>{{ $patient->full_name }}</h6>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Gender</label>
                        <h6>{{ $patient->patient_gender }}</h6>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Phone</label>
                        <h6>{{ $patient->patient_phone }}</h6>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Patient Type</label>
                        <h6>
                            @if($patient->patient_type == 'HMO')
                                <span class="badge bg-success">{{ $patient->patient_type }}</span>
                            @elseif($patient->patient_type == 'Private')
                                <span class="badge bg-warning text-dark">{{ $patient->patient_type }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $patient->patient_type }}</span>
                            @endif
                        </h6>
                    </div>
                    <div class="mb-0">
                        <label class="text-muted small">Total Consultancy Paid</label>
                        <h6>{{ $patient->total_consultancy_paid }} time(s)</h6>
                    </div>
                </div>
            </div>

            <!-- Consultancy History -->
            @if($patient->last_consultancy_date)
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Last Consultancy</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Paid On:</strong> {{ $patient->last_consultancy_date->format('M d, Y') }}</p>
                    <p class="mb-0"><strong>Expired On:</strong> {{ $patient->consultancy_expires_at->format('M d, Y') }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Verification Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Verify Payment Receipt from Accountant</h5>
                </div>
                <div class="card-body">
                    <!-- Payment Required Alert -->
                    @if($consultancyStatus['status'] == 'never_paid')
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>First Visit:</strong> This patient has never paid consultancy fee. Patient must go to Accountant Department first to make payment.
                    </div>
                    @elseif($consultancyStatus['status'] == 'expired')
                    <div class="alert alert-danger">
                        <i class="bi bi-x-circle me-2"></i>
                        <strong>Consultancy Expired:</strong> Previous consultancy expired on {{ $consultancyStatus['expired_on'] }}.
                        Patient must go to Accountant Department to pay a new consultancy fee.
                    </div>
                    @endif

                    <!-- Instructions -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="mb-3"><i class="bi bi-info-circle me-2"></i>Payment Process</h6>
                                    <ol class="mb-0">
                                        <li>Patient goes to <strong>Accountant Department</strong></li>
                                        <li>Patient pays consultancy fee and receives <strong>payment receipt</strong></li>
                                        <li>Patient brings receipt to <strong>Records Department</strong></li>
                                        <li>Records staff verifies receipt details below</li>
                                        <li>System activates 7-day consultancy validity</li>
                                        <li>Patient file can now be opened</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Verification Form -->
                    <form method="POST" action="{{ route('admin.records.patients.confirm-consultancy-payment', $patient->id) }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Receipt Number <span class="text-danger">*</span></label>
                                <input type="text" name="receipt_number" class="form-control" placeholder="Enter receipt number" required>
                                @error('receipt_number')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Amount Paid (₦) <span class="text-danger">*</span></label>
                                <input type="number" name="amount_paid" class="form-control" placeholder="Enter amount from receipt" step="0.01" min="0" required>
                                @error('amount_paid')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="" selected disabled>-- Select from Receipt --</option>
                                    <option value="Cash">Cash</option>
                                    <option value="POS">POS/Card</option>
                                    <option value="Transfer">Bank Transfer</option>
                                    @if($patient->patient_type == 'HMO')
                                    <option value="HMO">HMO Coverage</option>
                                    @endif
                                </select>
                                @error('payment_method')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                @error('payment_date')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Verification Note</label>
                                <textarea name="verification_note" class="form-control" rows="2" placeholder="Any additional notes about the payment verification..."></textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="verifyCheck" required>
                                    <label class="form-check-label" for="verifyCheck">
                                        I confirm that I have physically verified the payment receipt from the Accountant Department
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Box -->
                        <div class="alert alert-success">
                            <h6 class="mb-2"><i class="bi bi-check-circle me-2"></i>After Verification:</h6>
                            <ul class="mb-0">
                                <li>Consultancy will be valid for <strong>7 days</strong> (from payment date)</li>
                                <li>Patient file will be available to open</li>
                                <li>Patient can visit without paying consultancy for the next 7 days</li>
                                <li>Verification will be logged in the system</li>
                            </ul>
                        </div>

                        <div class="text-end mt-4">
                            <a href="{{ route('admin.records.patients.open') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i> Verify Payment & Activate Consultancy
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection