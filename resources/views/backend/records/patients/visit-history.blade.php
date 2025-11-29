{{-- resources/views/backend/records/patients/visit-history.blade.php --}}
@extends('admin.admin_master')
@section('title', 'Patient Visit History')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h3 class="page-title">Patient Visit History & Consultancy Tracking</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.records.patients.index') }}">Patient Registration</a></li>
                    <li class="breadcrumb-item active">Visit History</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-primary">
                            <i class="bi bi-people-fill"></i>
                        </span>
                        <div class="dash-count">
                            <h5>{{ \App\Models\Patient::count() }}</h5>
                        </div>
                    </div>
                    <div class="dash-widget-info">
                        <h6 class="text-muted">Total Patients</h6>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-success">
                            <i class="bi bi-calendar-check"></i>
                        </span>
                        <div class="dash-count">
                            <h5>{{ \App\Models\Patient::whereDate('created_at', today())->count() }}</h5>
                        </div>
                    </div>
                    <div class="dash-widget-info">
                        <h6 class="text-muted">Visits Today</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-warning">
                            <i class="bi bi-receipt"></i>
                        </span>
                        <div class="dash-count">
                            <h5>{{ \App\Models\ConsultancyPayment::whereDate('payment_date', today())->count() }}</h5>
                        </div>
                    </div>
                    <div class="dash-widget-info">
                        <h6 class="text-muted">Consultancy Paid Today</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-info">
                            <i class="bi bi-check-circle"></i>
                        </span>
                        <div class="dash-count">
                            <h5>{{ \App\Models\Patient::where('has_active_consultancy', true)->count() }}</h5>
                        </div>
                    </div>
                    <div class="dash-widget-info">
                        <h6 class="text-muted">Active Consultancies</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filter Options</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Search by Card Number</label>
                            <input type="text" class="form-control" id="filterCard" placeholder="Enter card number">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Search by Name</label>
                            <input type="text" class="form-control" id="filterName" placeholder="Enter patient name">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Time Period</label>
                            <select class="form-select" id="filterPeriod">
                                <option value="all">All Time</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month" selected>This Month</option>
                                <option value="year">This Year</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Consultancy Status</label>
                            <select class="form-select" id="filterConsultancy">
                                <option value="all">All Patients</option>
                                <option value="active">Active Consultancy</option>
                                <option value="expired">Expired/No Payment</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-primary" id="applyFilters">
                            <i class="bi bi-funnel me-1"></i> Apply Filters
                        </button>
                        <button type="button" class="btn btn-secondary" id="resetFilters">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visit History Table -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">Recent Patient Visits</h5>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="exportToExcel()">
                                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="visitHistoryTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Card Number</th>
                                    <th>Patient Name</th>
                                    <th>Phone</th>
                                    <th>Patient Type</th>
                                    <th>Last Visit</th>
                                    <th>Consultancy Status</th>
                                    <th>Total Payments</th>
                                    <th>File Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="visitHistoryBody">
                                @php
                                    $patients = \App\Models\Patient::with(['consultancyPayments', 'latestConsultancyPayment'])
                                        ->latest('created_at')
                                        ->paginate(20);
                                @endphp
                                @forelse($patients as $key => $patient)
                                <tr data-card="{{ $patient->card_number }}" 
                                    data-name="{{ strtolower($patient->full_name) }}" 
                                    data-consultancy="{{ $patient->has_active_consultancy ? 'active' : 'expired' }}"
                                    data-date="{{ $patient->created_at->format('Y-m-d') }}">
                                    <td class="text-secondary">{{ $patients->firstItem() + $key }}</td>
                                    <td><strong>{{ $patient->card_number }}</strong></td>
                                    <td>{{ $patient->full_name }}</td>
                                    <td class="text-secondary">{{ $patient->patient_phone }}</td>
                                    <td>
                                        @if($patient->patient_type == 'HMO')
                                            <span class="badge bg-success">HMO</span>
                                        @elseif($patient->patient_type == 'Private')
                                            <span class="badge bg-warning text-dark">Private</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $patient->patient_type }}</span>
                                        @endif
                                    </td>
                                    <td class="text-secondary">{{ $patient->created_at->format('M d, Y h:i A') }}</td>
                                    <td>
                                        @if($patient->hasActiveConsultancy())
                                            @php
                                                $status = $patient->getConsultancyStatus();
                                            @endphp
                                            <span class="badge bg-success" title="{{ $status['message'] }}">
                                                <i class="bi bi-check-circle me-1"></i>Active ({{ $patient->consultancyDaysRemaining() }}d)
                                            </span>
                                            <br>
                                            <small class="text-muted">Receipt: {{ $status['receipt_number'] ?? 'N/A' }}</small>
                                        @else
                                            @php
                                                $lastPayment = $patient->latestConsultancyPayment;
                                            @endphp
                                            @if($lastPayment)
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle me-1"></i>Expired
                                                </span>
                                                <br>
                                                <small class="text-muted">Last: {{ $lastPayment->consultancy_expiry_date->format('M d') }}</small>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-exclamation-circle me-1"></i>Never Paid
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ $patient->total_consultancy_paid }}</strong>
                                        @if($patient->total_consultancy_paid > 0)
                                        <br>
                                        <small class="text-muted">₦{{ number_format($patient->total_consultancy_amount, 0) }}</small>
                                        @endif
                                    </td>
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
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.records.patients.show', $patient->id) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.records.patients.consultancy-history', $patient->id) }}" class="btn btn-sm btn-outline-warning" title="Payment History">
                                                <i class="bi bi-receipt"></i>
                                            </a>
                                            <a href="{{ route('admin.records.patients.open-file', $patient->id) }}" class="btn btn-sm btn-outline-success" title="Open File">
                                                <i class="bi bi-folder"></i>
                                            </a>
                                            <a href="{{ route('admin.records.patients.edit', $patient->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Patient">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5 empty-state">
                                        <i class="bi bi-inbox empty-icon"></i>
                                        <p class="empty-text">No patient visits found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($patients->hasPages())
                    <div class="mt-3">
                        {{ $patients->links('pagination::bootstrap-5') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Consultancy Payments -->
    <div class="row mt-4">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-receipt me-2"></i>Recent Consultancy Payments
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Receipt No.</th>
                                    <th>Patient</th>
                                    <th>Payment Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Valid Until</th>
                                    <th>Status</th>
                                    <th>Verified By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $recentPayments = \App\Models\ConsultancyPayment::with(['patient', 'verifiedBy'])
                                        ->latest('payment_date')
                                        ->take(10)
                                        ->get();
                                @endphp
                                @forelse($recentPayments as $key => $payment)
                                <tr>
                                    <td class="text-secondary">{{ $key + 1 }}</td>
                                    <td><strong>{{ $payment->receipt_number }}</strong></td>
                                    <td>
                                        <a href="{{ route('admin.records.patients.show', $payment->patient_id) }}">
                                            {{ $payment->patient->full_name ?? 'N/A' }}
                                        </a>
                                        <br>
                                        <small class="text-muted">{{ $payment->patient->card_number ?? 'N/A' }}</small>
                                    </td>
                                    <td class="text-secondary">{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td class="text-success"><strong>₦{{ number_format($payment->amount_paid, 2) }}</strong></td>
                                    <td><span class="badge bg-info">{{ $payment->payment_method }}</span></td>
                                    <td class="text-secondary">{{ $payment->consultancy_expiry_date->format('M d, Y') }}</td>
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
                                        <br>
                                        <small>{{ $payment->verified_at->format('M d, h:i A') }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="bi bi-inbox"></i>
                                        <p class="mb-0">No recent payments</p>
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

@push('scripts')
<script>
    // Filter functionality
    document.getElementById('applyFilters').addEventListener('click', applyFilters);
    document.getElementById('resetFilters').addEventListener('click', resetFilters);

    function applyFilters() {
        const cardFilter = document.getElementById('filterCard').value.toUpperCase().trim();
        const nameFilter = document.getElementById('filterName').value.toLowerCase().trim();
        const periodFilter = document.getElementById('filterPeriod').value;
        const consultancyFilter = document.getElementById('filterConsultancy').value;
        
        const rows = document.querySelectorAll('#visitHistoryBody tr[data-card]');
        let visibleCount = 0;
        
        rows.forEach(row => {
            let show = true;
            
            // Card filter
            if (cardFilter && !row.dataset.card.includes(cardFilter)) {
                show = false;
            }
            
            // Name filter
            if (nameFilter && !row.dataset.name.includes(nameFilter)) {
                show = false;
            }
            
            // Consultancy filter
            if (consultancyFilter !== 'all' && row.dataset.consultancy !== consultancyFilter) {
                show = false;
            }
            
            // Period filter
            if (periodFilter !== 'all') {
                const rowDate = new Date(row.dataset.date);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                let showByDate = false;
                
                if (periodFilter === 'today') {
                    showByDate = rowDate.toDateString() === today.toDateString();
                } else if (periodFilter === 'week') {
                    const weekAgo = new Date(today);
                    weekAgo.setDate(weekAgo.getDate() - 7);
                    showByDate = rowDate >= weekAgo;
                } else if (periodFilter === 'month') {
                    const monthAgo = new Date(today);
                    monthAgo.setMonth(monthAgo.getMonth() - 1);
                    showByDate = rowDate >= monthAgo;
                } else if (periodFilter === 'year') {
                    const yearAgo = new Date(today);
                    yearAgo.setFullYear(yearAgo.getFullYear() - 1);
                    showByDate = rowDate >= yearAgo;
                }
                
                if (!showByDate) show = false;
            }
            
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });
        
        // Show message if no results
        if (visibleCount === 0) {
            alert('No patients match the selected filters.');
        }
    }

    function resetFilters() {
        document.getElementById('filterCard').value = '';
        document.getElementById('filterName').value = '';
        document.getElementById('filterPeriod').value = 'month';
        document.getElementById('filterConsultancy').value = 'all';
        
        const rows = document.querySelectorAll('#visitHistoryBody tr[data-card]');
        rows.forEach(row => {
            row.style.display = '';
        });
    }

    function exportToExcel() {
        // Simple CSV export
        const table = document.getElementById('visitHistoryTable');
        let csv = [];
        
        // Headers
        const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent.trim());
        csv.push(headers.join(','));
        
        // Rows (only visible ones)
        const rows = table.querySelectorAll('tbody tr[data-card]');
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                const cols = Array.from(row.querySelectorAll('td')).map(td => {
                    let text = td.textContent.trim().replace(/\s+/g, ' ');
                    // Remove action button text
                    if (text.includes('Actions')) text = '';
                    return `"${text}"`;
                });
                csv.push(cols.join(','));
            }
        });
        
        // Download
        const csvContent = csv.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'visit-history-' + new Date().toISOString().split('T')[0] + '.csv';
        a.click();
    }

    // Auto-apply filters on page load (default to this month)
    document.addEventListener('DOMContentLoaded', function() {
        applyFilters();
    });
</script>
@endpush

<style>
@media print {
    .page-header,
    .card-header button,
    .btn-group,
    .pagination {
        display: none !important;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
}

.dash-widget-header {
    display: flex;
    align-items: center;
    gap: 15px;
}

.dash-widget-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: rgba(var(--bs-primary-rgb), 0.1);
    font-size: 28px;
}

.dash-widget-icon.text-success {
    background: rgba(var(--bs-success-rgb), 0.1);
}

.dash-widget-icon.text-warning {
    background: rgba(var(--bs-warning-rgb), 0.1);
}

.dash-widget-icon.text-info {
    background: rgba(var(--bs-info-rgb), 0.1);
}

.dash-count h5 {
    margin: 0;
    font-size: 32px;
    font-weight: 700;
}

.dash-widget-info h6 {
    margin: 10px 0 0 0;
    font-size: 14px;
}
</style>