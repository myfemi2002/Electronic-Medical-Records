@extends('admin.admin_master')
@section('title', 'Doctor Queue')
@section('admin')
<div class="page-header">
    <h1>Doctor Module</h1>
    <p>Consultation workspace with SOAP notes, diagnosis, prescriptions, and investigation requests.</p>
</div>
<div class="mb-4">
    <a class="btn btn-outline-primary" href="{{ route('admin.doctor.reports') }}">View Reports</a>
</div>
<div class="card">
    <div class="card-header"><h5 class="card-title">Doctor Queue</h5></div>
    <div class="card-body">
        @include('backend.hms.partials.visit-table', ['visits' => $visits, 'route' => fn($visit) => route('admin.doctor.show', $visit)])
    </div>
</div>
@endsection
