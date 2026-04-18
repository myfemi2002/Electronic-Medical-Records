@extends('admin.admin_master')
@section('title', 'HMS Dashboard')
@section('admin')
<div class="page-header">
    <h1>Hospital Management Dashboard</h1>
    <p>Unified EMR and patient flow control with role-aware module access.</p>
    <span class="role-badge">{{ auth()->user()->getRoleNames()->implode(', ') ?: ucfirst(auth()->user()->role ?? 'Staff') }}</span>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stats-card">
            <h6>Visits Today</h6>
            <h3>{{ $stats['visits_today'] }}</h3>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stats-card">
            <h6>Waiting Queue</h6>
            <h3>{{ $stats['waiting_queue'] }}</h3>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stats-card">
            <h6>Revenue Today</h6>
            <h3>N{{ number_format($stats['revenue_today'], 2) }}</h3>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stats-card">
            <h6>Pending Orders</h6>
            <h3>{{ $stats['pending_orders'] }}</h3>
        </div>
    </div>
</div>

<div class="section-header">
    <h4>Clinical Workflow</h4>
    <p>Each card below respects permissions and links straight into that module’s live workspace.</p>
</div>

<div class="row g-3">
    @can('records.access')
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('admin.hms.visits.index') }}" class="dashboard-tile">
                <div class="tile-icon"><i class="bi bi-folder2-open"></i></div>
                <h5 class="tile-title">Records</h5>
                <p class="tile-description">Registration, visits, patient search, and handoff to cashier.</p>
            </a>
        </div>
    @endcan
    @can('cashier.access')
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('admin.cashier.index') }}" class="dashboard-tile">
                <div class="tile-icon"><i class="bi bi-cash-stack"></i></div>
                <h5 class="tile-title">Cashier</h5>
                <p class="tile-description">Invoices, receipts, refunds, and payment confirmation.</p>
            </a>
        </div>
    @endcan
    @can('triage.access')
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('admin.triage.queue-management') }}" class="dashboard-tile">
                <div class="tile-icon"><i class="bi bi-heart-pulse"></i></div>
                <h5 class="tile-title">Triage</h5>
                <p class="tile-description">Queue management, vitals capture, and clinical handoff.</p>
            </a>
        </div>
    @endcan
    @can('doctor.access')
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('admin.doctor.index') }}" class="dashboard-tile">
                <div class="tile-icon"><i class="bi bi-person-badge"></i></div>
                <h5 class="tile-title">Doctor</h5>
                <p class="tile-description">SOAP notes, ICD-10 diagnosis, prescriptions, and requests.</p>
            </a>
        </div>
    @endcan
    @can('nurse.access')
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('admin.nurse.index') }}" class="dashboard-tile">
                <div class="tile-icon"><i class="bi bi-hospital"></i></div>
                <h5 class="tile-title">Nurse</h5>
                <p class="tile-description">MAR, procedures, ward notes, fluid balance, and discharge.</p>
            </a>
        </div>
    @endcan
    @can('pharmacy.access')
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('admin.pharmacy.index') }}" class="dashboard-tile">
                <div class="tile-icon"><i class="bi bi-capsule-pill"></i></div>
                <h5 class="tile-title">Pharmacy</h5>
                <p class="tile-description">Prescription queue, dispensing, stock, expiry, and OTC support.</p>
            </a>
        </div>
    @endcan
    @can('laboratory.access')
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('admin.laboratory.index') }}" class="dashboard-tile">
                <div class="tile-icon"><i class="bi bi-clipboard2-pulse"></i></div>
                <h5 class="tile-title">Laboratory</h5>
                <p class="tile-description">Samples, barcode-ready tracking, result entry, and approval.</p>
            </a>
        </div>
    @endcan
    @can('radiology.access')
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('admin.radiology.index') }}" class="dashboard-tile">
                <div class="tile-icon"><i class="bi bi-badge-tm"></i></div>
                <h5 class="tile-title">Radiology</h5>
                <p class="tile-description">Imaging requests, report templates, comparisons, and release.</p>
            </a>
        </div>
    @endcan
    @can('reports.view')
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('admin.records.patients.reports') }}" class="dashboard-tile">
                <div class="tile-icon"><i class="bi bi-graph-up-arrow"></i></div>
                <h5 class="tile-title">Reports</h5>
                <p class="tile-description">Daily summaries, activity, financial breakdowns, and throughput.</p>
            </a>
        </div>
    @endcan
    @can('rbac.manage')
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('roleswithpermission.view') }}" class="dashboard-tile">
                <div class="tile-icon"><i class="bi bi-shield-lock"></i></div>
                <h5 class="tile-title">RBAC</h5>
                <p class="tile-description">Roles, permissions, module visibility, and URL protection.</p>
            </a>
        </div>
    @endcan
</div>

@if($stats['low_stock'] > 0)
    <div class="alert alert-warning mt-4">
        {{ $stats['low_stock'] }} inventory item(s) are at or below reorder level.
    </div>
@endif
@endsection
