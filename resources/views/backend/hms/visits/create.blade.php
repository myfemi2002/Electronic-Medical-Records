@extends('admin.admin_master')
@section('title', 'Create Visit')
@section('admin')
<div class="page-header">
    <h1>Create Visit</h1>
    <p>Open a new visit for {{ $patient->full_name }} and push it to the next billing step.</p>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.hms.visits.store') }}">
            @csrf
            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Visit Type</label>
                    <select class="form-select" name="visit_type">
                        <option value="outpatient">Outpatient</option>
                        <option value="follow_up">Follow Up</option>
                        <option value="emergency">Emergency</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Emergency Visit</label>
                    <select class="form-select" name="is_emergency">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Chief Complaint</label>
                    <textarea class="form-control" rows="3" name="chief_complaint">{{ old('chief_complaint') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Records Note</label>
                    <textarea class="form-control" rows="3" name="notes">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Create Visit and Push to Cashier</button>
                <a class="btn btn-secondary" href="{{ route('admin.records.patients.show', $patient->id) }}">Back to Patient</a>
            </div>
        </form>
    </div>
</div>
@endsection
