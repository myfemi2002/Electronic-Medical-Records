@extends('admin.admin_master')
@section('title', 'Nurse Workspace')
@section('admin')
<div class="page-header">
    <h1>Nurse Workspace</h1>
    <p>{{ $visit->patient->full_name }} • {{ $visit->visit_number }}</p>
</div>

<div class="card">
    <div class="card-header"><h5 class="card-title">Add Nursing Note</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.nurse.notes', $visit) }}">
            @csrf
            <div class="row g-3">
                <div class="col-12"><label class="form-label">Note</label><textarea class="form-control" rows="3" name="note"></textarea></div>
                <div class="col-md-6"><label class="form-label">Medication Administration</label><textarea class="form-control" rows="2" name="medication_administration"></textarea></div>
                <div class="col-md-6"><label class="form-label">Procedures</label><textarea class="form-control" rows="2" name="procedures"></textarea></div>
                <div class="col-md-6"><label class="form-label">Bed Allocation</label><input class="form-control" name="bed_allocation"></div>
                <div class="col-md-6"><label class="form-label">Fluid Balance</label><textarea class="form-control" rows="2" name="fluid_balance"></textarea></div>
                <div class="col-md-4">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="complete_visit" value="1" id="complete_visit">
                        <label class="form-check-label" for="complete_visit">Discharge after note</label>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary mt-3" type="submit">Save Nursing Note</button>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header"><h5 class="card-title">Existing Notes</h5></div>
    <div class="card-body">
        @forelse($visit->nursingNotes as $note)
            <div class="border rounded p-3 mb-3">
                <div class="small text-muted">{{ $note->created_at->format('d M Y, h:i A') }}</div>
                <div>{{ $note->note }}</div>
            </div>
        @empty
            <p class="text-muted mb-0">No nursing notes recorded yet.</p>
        @endforelse
    </div>
</div>
@endsection
