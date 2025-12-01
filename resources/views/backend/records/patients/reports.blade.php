{{-- resources/views/backend/records/patients/reports.blade.php --}}
@extends('admin.admin_master')
@section('title', 'Patient Reports & Analytics')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h3 class="page-title">Patient Reports & Analytics</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.records.patients.index') }}">Patient Registration</a></li>
                    <li class="breadcrumb-item active">Reports</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <!-- Export Buttons -->
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-success" onclick="exportToExcel()">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export to Excel
                </button>
                <button type="button" class="btn btn-danger" onclick="exportToPDF()">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Export to PDF
                </button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Print Report
                </button>
            </div>
        </div>
    </div>

    <!-- Patient Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3"><i class="bi bi-bar-chart-fill me-2"></i>Patient Statistics Overview</h5>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 mb-3">
            <div class="card border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Patients</h6>
                            <h2 class="mb-0">{{ number_format($stats['total_patients']) }}</h2>
                        </div>
                        <div class="stat-icon bg-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 mb-3">
            <div class="card border-start border-4 border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Male Patients</h6>
                            <h2 class="mb-0">{{ number_format($stats['male_patients']) }}</h2>
                            <small class="text-muted">
                                {{ $stats['total_patients'] > 0 ? number_format(($stats['male_patients'] / $stats['total_patients']) * 100, 1) : 0 }}%
                            </small>
                        </div>
                        <div class="stat-icon bg-info">
                            <i class="bi bi-gender-male"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 mb-3">
            <div class="card border-start border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Female Patients</h6>
                            <h2 class="mb-0">{{ number_format($stats['female_patients']) }}</h2>
                            <small class="text-muted">
                                {{ $stats['total_patients'] > 0 ? number_format(($stats['female_patients'] / $stats['total_patients']) * 100, 1) : 0 }}%
                            </small>
                        </div>
                        <div class="stat-icon bg-danger">
                            <i class="bi bi-gender-female"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 mb-3">
            <div class="card border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">New This Month</h6>
                            <h2 class="mb-0">{{ number_format($stats['new_this_month']) }}</h2>
                        </div>
                        <div class="stat-icon bg-success">
                            <i class="bi bi-calendar-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Patient Type Distribution -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3"><i class="bi bi-pie-chart-fill me-2"></i>Patient Type Distribution</h5>
        </div>

        <div class="col-xl-4 col-sm-6 col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">HMO Patients</h6>
                            <h2 class="mb-0 text-success">{{ number_format($stats['hmo_patients']) }}</h2>
                            <small class="text-muted">
                                {{ $stats['total_patients'] > 0 ? number_format(($stats['hmo_patients'] / $stats['total_patients']) * 100, 1) : 0 }}%
                            </small>
                        </div>
                        <div class="stat-icon bg-success">
                            <i class="bi bi-hospital"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Private Patients</h6>
                            <h2 class="mb-0 text-warning">{{ number_format($stats['private_patients']) }}</h2>
                            <small class="text-muted">
                                {{ $stats['total_patients'] > 0 ? number_format(($stats['private_patients'] / $stats['total_patients']) * 100, 1) : 0 }}%
                            </small>
                        </div>
                        <div class="stat-icon bg-warning">
                            <i class="bi bi-person-badge"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Retainership</h6>
                            <h2 class="mb-0 text-secondary">{{ number_format($stats['retainership_patients']) }}</h2>
                            <small class="text-muted">
                                {{ $stats['total_patients'] > 0 ? number_format(($stats['retainership_patients'] / $stats['total_patients']) * 100, 1) : 0 }}%
                            </small>
                        </div>
                        <div class="stat-icon bg-secondary">
                            <i class="bi bi-briefcase"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Consultancy Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3"><i class="bi bi-receipt-cutoff me-2"></i>Consultancy Payment Statistics</h5>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 mb-3">
            <div class="card border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Active Consultancies</h6>
                            <h2 class="mb-0 text-warning">{{ number_format($stats['active_consultancies']) }}</h2>
                        </div>
                        <div class="stat-icon bg-warning">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 mb-3">
            <div class="card border-start border-4 border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Payments</h6>
                            <h2 class="mb-0">{{ number_format($consultancyStats['total_payments']) }}</h2>
                        </div>
                        <div class="stat-icon bg-info">
                            <i class="bi bi-receipt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 mb-3">
            <div class="card border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Revenue</h6>
                            <h2 class="mb-0 text-success">₦{{ number_format($consultancyStats['total_revenue'], 2) }}</h2>
                        </div>
                        <div class="stat-icon bg-success">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 mb-3">
            <div class="card border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Payments Today</h6>
                            <h2 class="mb-0">{{ number_format($consultancyStats['today_payments']) }}</h2>
                        </div>
                        <div class="stat-icon bg-primary">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- File Management Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3"><i class="bi bi-folder2-open me-2"></i>File Management Statistics</h5>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Open Files Today</h6>
                            <h2 class="mb-0 text-success">{{ number_format($stats['open_files_today']) }}</h2>
                        </div>
                        <div class="stat-icon bg-success">
                            <i class="bi bi-folder-open"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">This Month</h6>
                            <h2 class="mb-0">{{ number_format($consultancyStats['this_month_payments']) }}</h2>
                        </div>
                        <div class="stat-icon bg-info">
                            <i class="bi bi-calendar-month"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Expired Consultancies</h6>
                            <h2 class="mb-0 text-danger">{{ number_format($consultancyStats['expired_consultancies']) }}</h2>
                        </div>
                        <div class="stat-icon bg-danger">
                            <i class="bi bi-x-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">New This Year</h6>
                            <h2 class="mb-0">{{ number_format($stats['new_this_year']) }}</h2>
                        </div>
                        <div class="stat-icon bg-secondary">
                            <i class="bi bi-calendar2-range"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Gender Distribution Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Gender Distribution</h5>
                </div>
                <div class="card-body">
                    <div id="genderChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>

        <!-- Patient Type Distribution Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Patient Type Distribution</h5>
                </div>
                <div class="card-body">
                    <div id="patientTypeChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>

        <!-- Monthly Registration Trend -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Monthly Patient Registration Trend (Last 12 Months)</h5>
                </div>
                <div class="card-body">
                    <div id="monthlyTrendChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Consultancy Revenue by Payment Method -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Consultancy Revenue by Payment Method</h5>
                </div>
                <div class="card-body">
                    <div id="paymentMethodChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Consultancy Status Overview</h5>
                </div>
                <div class="card-body">
                    <div id="consultancyStatusChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Summary Report</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="summaryTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Category</th>
                                    <th class="text-end">Count</th>
                                    <th class="text-end">Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Total Patients</strong></td>
                                    <td class="text-end"><strong>{{ number_format($stats['total_patients']) }}</strong></td>
                                    <td class="text-end">100%</td>
                                </tr>
                                <tr>
                                    <td>Male Patients</td>
                                    <td class="text-end">{{ number_format($stats['male_patients']) }}</td>
                                    <td class="text-end">
                                        {{ $stats['total_patients'] > 0 ? number_format(($stats['male_patients'] / $stats['total_patients']) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>Female Patients</td>
                                    <td class="text-end">{{ number_format($stats['female_patients']) }}</td>
                                    <td class="text-end">
                                        {{ $stats['total_patients'] > 0 ? number_format(($stats['female_patients'] / $stats['total_patients']) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>HMO Patients</td>
                                    <td class="text-end">{{ number_format($stats['hmo_patients']) }}</td>
                                    <td class="text-end">
                                        {{ $stats['total_patients'] > 0 ? number_format(($stats['hmo_patients'] / $stats['total_patients']) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>Private Patients</td>
                                    <td class="text-end">{{ number_format($stats['private_patients']) }}</td>
                                    <td class="text-end">
                                        {{ $stats['total_patients'] > 0 ? number_format(($stats['private_patients'] / $stats['total_patients']) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>Retainership Patients</td>
                                    <td class="text-end">{{ number_format($stats['retainership_patients']) }}</td>
                                    <td class="text-end">
                                        {{ $stats['total_patients'] > 0 ? number_format(($stats['retainership_patients'] / $stats['total_patients']) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                                <tr class="table-light">
                                    <td><strong>Active Consultancies</strong></td>
                                    <td class="text-end"><strong>{{ number_format($stats['active_consultancies']) }}</strong></td>
                                    <td class="text-end">
                                        {{ $stats['total_patients'] > 0 ? number_format(($stats['active_consultancies'] / $stats['total_patients']) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total Consultancy Payments</td>
                                    <td class="text-end">{{ number_format($consultancyStats['total_payments']) }}</td>
                                    <td class="text-end">-</td>
                                </tr>
                                <tr>
                                    <td>Total Consultancy Revenue</td>
                                    <td class="text-end" colspan="2"><strong>₦{{ number_format($consultancyStats['total_revenue'], 2) }}</strong></td>
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

@push('css')
<style>
/* Reports Page Dark Mode Styling */
.stat-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    color: white;
    font-size: 28px;
}

.card {
    transition: transform 0.2s;
    background: var(--card-bg);
    border: 1px solid var(--border-color);
}

.card:hover {
    transform: translateY(-5px);
}

.card-header {
    background: var(--tile-bg);
    border-bottom: 1px solid var(--border-color);
}

.card-body {
    background: var(--card-bg);
}

/* Border Color Classes */
.border-primary { border-color: var(--primary-color) !important; }
.border-info { border-color: var(--info-color) !important; }
.border-danger { border-color: var(--danger-color) !important; }
.border-success { border-color: var(--success-color) !important; }
.border-warning { border-color: var(--warning-color) !important; }

/* Background Color Classes for Stat Icons */
.bg-primary { background-color: var(--primary-color) !important; }
.bg-info { background-color: var(--info-color) !important; }
.bg-danger { background-color: var(--danger-color) !important; }
.bg-success { background-color: var(--success-color) !important; }
.bg-warning { background-color: var(--warning-color) !important; }
.bg-secondary { background-color: #6c757d !important; }

/* Text Color Classes */
.text-success { color: var(--success-color) !important; }
.text-warning { color: var(--warning-color) !important; }
.text-danger { color: var(--danger-color) !important; }
.text-info { color: var(--info-color) !important; }
.text-muted { color: var(--text-muted) !important; }
.text-secondary { color: var(--text-secondary) !important; }

/* Table Styling */
.table {
    color: var(--text-primary);
    background: transparent;
}

.table thead {
    background: transparent;
}

.table thead th {
    color: var(--text-primary) !important;
    background: transparent !important;
    border-color: var(--border-color);
}

.table tbody tr {
    background: transparent !important;
    border-color: var(--border-color);
}

.table tbody tr:hover {
    background: var(--hover-bg) !important;
}

.table tbody td {
    color: var(--text-primary) !important;
    border-color: var(--border-color);
}

.table-bordered {
    border-color: var(--border-color);
}

.table-bordered th,
.table-bordered td {
    border-color: var(--border-color);
}

.table-light {
    background: var(--tile-bg) !important;
    color: var(--text-primary) !important;
}

.table-light th,
.table-light td {
    background: var(--tile-bg) !important;
    color: var(--text-primary) !important;
}

/* Chart Containers */
#genderChart,
#patientTypeChart,
#monthlyTrendChart,
#paymentMethodChart,
#consultancyStatusChart {
    background: transparent;
}

/* Print Styles */
@media print {
    .page-header,
    .btn,
    .breadcrumb {
        display: none !important;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
        page-break-inside: avoid;
    }
    
    .row {
        page-break-inside: avoid;
    }
    
    body {
        background: white !important;
    }
    
    .card-body,
    .card-header,
    .table {
        background: white !important;
        color: #000 !important;
    }
}

/* Heading Colors */
h5, h6 {
    color: var(--text-primary);
}

/* Small Text in Cards */
.card-body small {
    color: var(--text-muted);
}

/* Export Buttons */
.btn-success {
    background-color: var(--success-color);
    border-color: var(--success-color);
}

.btn-danger {
    background-color: var(--danger-color);
    border-color: var(--danger-color);
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

/* Number Formatting */
h2, h3, h4, h5 {
    color: var(--text-primary);
}

/* Icons */
.bi {
    color: inherit;
}
</style>
@endpush

@push('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    // Load Google Charts
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawCharts);

    // Get current theme
    function getCurrentTheme() {
        return document.documentElement.getAttribute('data-theme') || 'dark';
    }

    // Get chart colors based on theme
    function getChartOptions(baseOptions) {
        const isDark = getCurrentTheme() === 'dark';
        
        return {
            ...baseOptions,
            backgroundColor: 'transparent',
            legend: { 
                ...baseOptions.legend,
                textStyle: { color: isDark ? '#e2e8f0' : '#1e293b' }
            },
            titleTextStyle: { 
                color: isDark ? '#e2e8f0' : '#1e293b' 
            },
            hAxis: {
                ...baseOptions.hAxis,
                textStyle: { color: isDark ? '#e2e8f0' : '#1e293b' },
                gridlines: { color: isDark ? '#334155' : '#e2e8f0' }
            },
            vAxis: {
                ...baseOptions.vAxis,
                textStyle: { color: isDark ? '#e2e8f0' : '#1e293b' },
                gridlines: { color: isDark ? '#334155' : '#e2e8f0' }
            }
        };
    }

    function drawCharts() {
        drawGenderChart();
        drawPatientTypeChart();
        drawMonthlyTrendChart();
        drawPaymentMethodChart();
        drawConsultancyStatusChart();
    }

    // Gender Distribution Chart
    function drawGenderChart() {
        const data = google.visualization.arrayToDataTable([
            ['Gender', 'Count'],
            ['Male', {{ $stats['male_patients'] }}],
            ['Female', {{ $stats['female_patients'] }}]
        ]);

        const options = getChartOptions({
            pieHole: 0.4,
            colors: ['#0d6efd', '#dc3545'],
            legend: { position: 'bottom' },
            chartArea: { width: '90%', height: '75%' }
        });

        const chart = new google.visualization.PieChart(document.getElementById('genderChart'));
        chart.draw(data, options);
    }

    // Patient Type Distribution Chart
    function drawPatientTypeChart() {
        const data = google.visualization.arrayToDataTable([
            ['Type', 'Count'],
            ['HMO', {{ $stats['hmo_patients'] }}],
            ['Private', {{ $stats['private_patients'] }}],
            ['Retainership', {{ $stats['retainership_patients'] }}]
        ]);

        const options = getChartOptions({
            pieHole: 0.4,
            colors: ['#198754', '#ffc107', '#6c757d'],
            legend: { position: 'bottom' },
            chartArea: { width: '90%', height: '75%' }
        });

        const chart = new google.visualization.PieChart(document.getElementById('patientTypeChart'));
        chart.draw(data, options);
    }

    // Monthly Registration Trend
    function drawMonthlyTrendChart() {
        @php
            $monthlyData = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $count = \App\Models\Patient::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                $monthlyData[] = [
                    'month' => $date->format('M Y'),
                    'count' => $count
                ];
            }
        @endphp

        const data = google.visualization.arrayToDataTable([
            ['Month', 'Registrations'],
            @foreach($monthlyData as $item)
                ['{{ $item["month"] }}', {{ $item["count"] }}],
            @endforeach
        ]);

        const options = getChartOptions({
            curveType: 'function',
            legend: { position: 'bottom' },
            colors: ['#0d6efd'],
            chartArea: { width: '85%', height: '70%' },
            hAxis: { slantedText: true, slantedTextAngle: 45 }
        });

        const chart = new google.visualization.LineChart(document.getElementById('monthlyTrendChart'));
        chart.draw(data, options);
    }

    // Payment Method Distribution
    function drawPaymentMethodChart() {
        @php
            $paymentMethods = \App\Models\ConsultancyPayment::selectRaw('payment_method, SUM(amount_paid) as total')
                ->groupBy('payment_method')
                ->get();
        @endphp

        const data = google.visualization.arrayToDataTable([
            ['Payment Method', 'Revenue'],
            @foreach($paymentMethods as $method)
                ['{{ $method->payment_method }}', {{ $method->total }}],
            @endforeach
        ]);

        const options = getChartOptions({
            pieHole: 0.4,
            colors: ['#198754', '#0dcaf0', '#ffc107', '#6c757d'],
            legend: { position: 'bottom' },
            chartArea: { width: '90%', height: '75%' }
        });

        const chart = new google.visualization.PieChart(document.getElementById('paymentMethodChart'));
        chart.draw(data, options);
    }

    // Consultancy Status Chart
    function drawConsultancyStatusChart() {
        const data = google.visualization.arrayToDataTable([
            ['Status', 'Count'],
            ['Active', {{ $consultancyStats['active_consultancies'] }}],
            ['Expired', {{ $consultancyStats['expired_consultancies'] }}],
            ['Never Paid', {{ $stats['total_patients'] - $stats['active_consultancies'] - $consultancyStats['expired_consultancies'] }}]
        ]);

        const options = getChartOptions({
            pieHole: 0.4,
            colors: ['#198754', '#dc3545', '#6c757d'],
            legend: { position: 'bottom' },
            chartArea: { width: '90%', height: '75%' }
        });

        const chart = new google.visualization.PieChart(document.getElementById('consultancyStatusChart'));
        chart.draw(data, options);
    }

    // Export Functions
    function exportToExcel() {
        const table = document.getElementById('summaryTable');
        let csv = [];
        
        // Add title
        csv.push(['Patient Reports & Analytics - Generated on ' + new Date().toLocaleDateString()]);
        csv.push(['']);
        
        // Headers
        const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent.trim());
        csv.push(headers.join(','));
        
        // Rows
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cols = Array.from(row.querySelectorAll('td')).map(td => `"${td.textContent.trim()}"`);
            csv.push(cols.join(','));
        });
        
        // Download
        const csvContent = csv.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'patient-report-' + new Date().toISOString().split('T')[0] + '.csv';
        a.click();
    }

    function exportToPDF() {
        window.print();
    }

    // Redraw charts on theme change
    if (window.toggleTheme) {
        const originalToggle = window.toggleTheme;
        window.toggleTheme = function() {
            originalToggle();
            setTimeout(drawCharts, 100);
        };
    }

    // Redraw charts on window resize
    window.addEventListener('resize', function() {
        drawCharts();
    });
</script>
@endpush

<style>
@media print {
    .page-header,
    .btn,
    .breadcrumb {
        display: none !important;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
        page-break-inside: avoid;
    }
    
    .row {
        page-break-inside: avoid;
    }
}

.stat-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    color: white;
    font-size: 28px;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}
</style>