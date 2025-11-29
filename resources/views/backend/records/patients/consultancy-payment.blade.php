{{-- resources/views/backend/records/patients/consultancy-payment.blade.php --}}
@extends('admin.admin_master')
@section('title', 'Consultancy Payment')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h3 class="page-title">Consultancy Payment Required</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.records.patients.index') }}">Patient Registration</a></li>
                    <li class="breadcrumb-item active">Consultancy Payment</li>
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
        </div>

        <!-- Consultancy Payment Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Consultancy Fee Payment</h5>
                </div>
                <div class="card-body">
                    <!-- Consultancy Status -->
                    @if($consultancyStatus['status'] == 'never_paid')
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>First Visit:</strong> This patient has never paid consultancy fee.
                    </div>
                    @elseif($consultancyStatus['status'] == 'expired')
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Consultancy Expired:</strong> Previous consultancy expired on {{ $consultancyStatus['expired_on'] }}.
                        A new consultancy fee is required.
                    </div>
                    @endif

                    <!-- Consultancy Information -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="mb-3"><i class="bi bi-info-circle me-2"></i>Consultancy Details</h6>
                                    <ul class="mb-0">
                                        <li>Consultancy fee is valid for <strong>7 days</strong></li>
                                        <li>Patient can visit multiple times within 7 days without paying again</li>
                                        <li>After 7 days (on day 8), a new consultancy fee is required</li>
                                        <li>Files automatically close at 11:59 PM daily</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <form method="POST" action="{{ route('admin.records.patients.process-consultancy-payment', $patient->id) }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="" selected disabled>-- Select Payment Method --</option>
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
                                <label class="form-label">Amount Paid (₦) <span class="text-danger">*</span></label>
                                <input type="number" name="amount_paid" class="form-control" placeholder="Enter amount" step="0.01" min="0" required>
                                @error('amount_paid')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Payment Note (Optional)</label>
                                <textarea name="payment_note" class="form-control" rows="2" placeholder="Additional notes..."></textarea>
                            </div>
                        </div>

                        <!-- Summary Box -->
                        <div class="alert alert-success">
                            <h6 class="mb-2"><i class="bi bi-check-circle me-2"></i>After Payment:</h6>
                            <ul class="mb-0">
                                <li>Consultancy will be valid from <strong>{{ now()->format('M d, Y') }}</strong> to <strong>{{ now()->addDays(7)->format('M d, Y') }}</strong></li>
                                <li>Patient file will be opened automatically</li>
                                <li>Patient can visit without consultancy fee for the next 7 days</li>
                            </ul>
                        </div>

                        <div class="text-end mt-4">
                            <a href="{{ route('admin.records.patients.open') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i> Process Payment & Open File
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection