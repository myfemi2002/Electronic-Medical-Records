{{-- resources/views/backend/records/patients/reports.blade.php --}}
@extends('admin.admin_master')
@section('title', 'Patient Reports')
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
                    <li class="breadcrumb-item active">Patient Reports</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stats-card">
                <h6><i class="bi bi-people me-1"></i> Total Patients</h6>
                <h3>{{ $stats['total_patients'] }}</h3>
                <span class="badge bg-primary">All time</span>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stats-card">
                <h6><i class="bi bi-gender-male me-1"></i> Male Patients</h6>
                <h3>{{ $stats['male_patients'] }}</h3>
                <span class="badge bg-info">{{ $stats['total_patients'] > 0 ? round(($stats['male_patients']/$stats['total_patients'])*100) : 0 }}%</span>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stats-card">
                <h6><i class="bi bi-gender-female me-1"></i> Female Patients</h6>
                <h3>{{ $stats['female_patients'] }}</h3>
                <span class="badge bg-danger">{{ $stats['total_patients'] > 0 ? round(($stats['female_patients']/$stats['total_patients'])*100) : 0 }}%</span>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stats-card">
                <h6><i class="bi bi-calendar-month me-1"></i> New This Month</h6>
                <h3>{{ $stats['new_this_month'] }}</h3>
                <span class="badge bg-success">{{ now()->format('M Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Patient Type Distribution -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stats-card">
                <h6><i class="bi bi-shield-check me-1"></i> HMO Patients</h6>
                <h3>{{ $stats['hmo_patients'] }}</h3>
                <span class="badge bg-success">Insurance</span>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stats-card">
                <h6><i class="bi bi-wallet2 me-1"></i> Private Patients</h6>
                <h3>{{ $stats['private_patients'] }}</h3>
                <span class="badge bg-warning text-dark">Self-pay</span>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stats-card">
                <h6><i class="bi bi-building me-1"></i> Retainership</h6>
                <h3>{{ $stats['retainership_patients'] }}</h3>
                <span class="badge bg-info">Corporate</span>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stats-card">
                <h6><i class="bi bi-calendar-event me-1"></i> New This Year</h6>
                <h3>{{ $stats['new_this_year'] }}</h3>
                <span class="badge bg-primary">{{ now()->format('Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Gender Distribution</h5>
                </div>
                <div class="card-body">
                    <div id="genderChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Patient Type Distribution</h5>
                </div>
                <div class="card-body">
                    <div id="patientTypeChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Registration Trend -->
    <div class="row mb-4">
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

    <!-- Export Options -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Export Reports</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <button type="button" class="btn btn-outline-primary w-100" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i> Print Report
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button type="button" class="btn btn-outline-success w-100">
                                <i class="bi bi-file-earmark-excel me-1"></i> Export to Excel
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button type="button" class="btn btn-outline-danger w-100">
                                <i class="bi bi-file-earmark-pdf me-1"></i> Export to PDF
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button type="button" class="btn btn-outline-info w-100">
                                <i class="bi bi-file-earmark-text me-1"></i> Export to CSV
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        drawGenderChart();
        drawPatientTypeChart();
        drawMonthlyTrendChart();
    }

    // Gender Distribution Pie Chart
    function drawGenderChart() {
        var data = google.visualization.arrayToDataTable([
            ['Gender', 'Count'],
            ['Male', {{ $stats['male_patients'] }}],
            ['Female', {{ $stats['female_patients'] }}]
        ]);

        var options = {
            title: 'Gender Distribution',
            pieHole: 0.4,
            colors: ['#4f46e5', '#ec4899'],
            backgroundColor: 'transparent',
            titleTextStyle: {
                color: getComputedStyle(document.documentElement).getPropertyValue('--text-primary').trim()
            },
            legend: {
                textStyle: {
                    color: getComputedStyle(document.documentElement).getPropertyValue('--text-primary').trim()
                }
            }
        };

        var chart = new google.visualization.PieChart(document.getElementById('genderChart'));
        chart.draw(data, options);
    }

    // Patient Type Distribution Pie Chart
    function drawPatientTypeChart() {
        var data = google.visualization.arrayToDataTable([
            ['Patient Type', 'Count'],
            ['HMO', {{ $stats['hmo_patients'] }}],
            ['Private', {{ $stats['private_patients'] }}],
            ['Retainership', {{ $stats['retainership_patients'] }}]
        ]);

        var options = {
            title: 'Patient Type Distribution',
            pieHole: 0.4,
            colors: ['#10b981', '#f59e0b', '#06b6d4'],
            backgroundColor: 'transparent',
            titleTextStyle: {
                color: getComputedStyle(document.documentElement).getPropertyValue('--text-primary').trim()
            },
            legend: {
                textStyle: {
                    color: getComputedStyle(document.documentElement).getPropertyValue('--text-primary').trim()
                }
            }
        };

        var chart = new google.visualization.PieChart(document.getElementById('patientTypeChart'));
        chart.draw(data, options);
    }

    // Monthly Trend Line Chart
    function drawMonthlyTrendChart() {
        @php
            $monthlyData = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $count = \App\Models\Patient::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                $monthlyData[] = [$date->format('M Y'), $count];
            }
        @endphp

        var data = google.visualization.arrayToDataTable([
            ['Month', 'Registrations'],
            @foreach($monthlyData as $data)
                ['{{ $data[0] }}', {{ $data[1] }}],
            @endforeach
        ]);

        var options = {
            title: 'Monthly Registration Trend',
            curveType: 'function',
            legend: { position: 'bottom' },
            backgroundColor: 'transparent',
            colors: ['#4f46e5'],
            titleTextStyle: {
                color: getComputedStyle(document.documentElement).getPropertyValue('--text-primary').trim()
            },
            hAxis: {
                textStyle: {
                    color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary').trim()
                }
            },
            vAxis: {
                textStyle: {
                    color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary').trim()
                }
            },
            legend: {
                textStyle: {
                    color: getComputedStyle(document.documentElement).getPropertyValue('--text-primary').trim()
                }
            }
        };

        var chart = new google.visualization.LineChart(document.getElementById('monthlyTrendChart'));
        chart.draw(data, options);
    }

    // Redraw charts on theme change
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            setTimeout(drawCharts, 350);
        });
    }

    // Redraw charts on window resize
    window.addEventListener('resize', function() {
        drawCharts();
    });
</script>
@endpush