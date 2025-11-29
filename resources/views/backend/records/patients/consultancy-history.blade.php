{{-- resources/views/backend/records/patients/consultancy-history.blade.php --}}
@extends('admin.admin_master')
@section('title', 'Consultancy Payment History')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h3 class="page-title">Consultancy Payment History</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.records.patients.index') }}">Patient Registration</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.records.patients.show', $patient->id) }}">{{ $patient->full_name }}</a></li>
                    <li class="breadcrumb-item active">Payment History</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="row">
        <!-- Patient Summary -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0 text-white">
                        <i class="bi bi-person-circle me-2"></i>Patient Details
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
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Payment Summary</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Total Payments</label>
                        <h5 class="mb-0">{{ $patient->total_consultancy_paid }} times</h5>
                    </div>
                    <div class="mb-0">
                        <label class="text-muted small">Total Amount</label>
                        <h5 class="mb-0 text-success">₦{{ number_format($patient->total_consultancy_amount, 2) }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment History Table -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Consultancy Payments</h5>
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
                                @forelse($payments as $key => $payment)
                                <tr>
                                    <td class="text-secondary">{{ $payments->firstItem() + $key }}</td>
                                    <td><strong>{{ $payment->receipt_number }}</strong></td>
                                    <td class="text-secondary">{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td class="text-success"><strong>₦{{ number_format($payment->amount_paid, 2) }}</strong></td>
                                    <td>
                                        <span class="badge bg-info">{{ $payment->payment_method }}</span>
                                    </td>
                                    <td class="text-secondary small">
                                        {{ $payment->consultancy_start_date->format('M d') }} - 
                                        {{ $payment->consultancy_expiry_date->format('M d, Y') }}
                                    </td>
                                    <td>
                                        @if($payment->status === 'active' && $payment->isActive())
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
                                        @if($payment->verifiedBy)
                                            {{ $payment->verifiedBy->name }}
                                        @else
                                            System
                                        @endif
                                    </td>
                                </tr>
                                <tr class="bg-light">
                                    <td colspan="8" class="small">
                                        <strong>Verified:</strong> {{ $payment->verified_at->format('M d, Y h:i A') }}
                                        @if($payment->verification_note)
                                        <br><strong>Note:</strong> {{ $payment->verification_note }}
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 empty-state">
                                        <i class="bi bi-inbox empty-icon"></i>
                                        <p class="empty-text">No payment history found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($payments->hasPages())
                    <div class="mt-3">
                        {{ $payments->links('pagination::bootstrap-5') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection