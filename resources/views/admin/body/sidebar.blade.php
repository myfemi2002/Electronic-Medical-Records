@php
    $assetBase = asset('backend/assets');
@endphp

<div id="sidebar-menu">
    <ul id="side-menu">
        <li class="menu-title">Main</li>
        <li><a class="tp-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        @can('records.access')<li><a class="tp-link" href="{{ route('admin.hms.visits.index') }}">Records / Visits</a></li>@endcan
        @can('cashier.access')<li><a class="tp-link" href="{{ route('admin.cashier.index') }}">Cashier</a></li>@endcan
        @can('triage.access')<li><a class="tp-link" href="{{ route('admin.triage.queue-management') }}">Triage</a></li>@endcan
        @can('doctor.access')<li><a class="tp-link" href="{{ route('admin.doctor.index') }}">Doctor</a></li>@endcan
        @can('nurse.access')<li><a class="tp-link" href="{{ route('admin.nurse.index') }}">Nurse</a></li>@endcan
        @can('pharmacy.access')<li><a class="tp-link" href="{{ route('admin.pharmacy.index') }}">Pharmacy</a></li>@endcan
        @can('laboratory.access')<li><a class="tp-link" href="{{ route('admin.laboratory.index') }}">Laboratory</a></li>@endcan
        @can('radiology.access')<li><a class="tp-link" href="{{ route('admin.radiology.index') }}">Radiology</a></li>@endcan
        @can('reports.view')<li><a class="tp-link" href="{{ route('admin.records.patients.reports') }}">Reports</a></li>@endcan
        @can('departments.manage')<li><a class="tp-link" href="{{ route('admin.departments.index') }}">Departments</a></li>@endcan
        @can('rbac.manage')<li><a class="tp-link" href="{{ route('roleswithpermission.view') }}">RBAC</a></li>@endcan
        @can('security.manage')<li><a class="tp-link" href="{{ route('admin.ip.index') }}">Security</a></li>@endcan
    </ul>
</div>
