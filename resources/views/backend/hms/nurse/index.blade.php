@extends('admin.admin_master')
@section('title', 'Nurse Queue')
@section('admin')
<div class="page-header">
    <h1>Nurse Module</h1>
    <p>Nursing notes, medication administration, procedures, ward tracking, and discharge support.</p>
</div>
<div class="card">
    <div class="card-header"><h5 class="card-title">Nurse Queue</h5></div>
    <div class="card-body">
        @include('backend.hms.partials.visit-table', ['visits' => $visits, 'route' => fn($visit) => route('admin.nurse.show', $visit)])
    </div>
</div>
@endsection
