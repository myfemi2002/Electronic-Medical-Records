@extends('admin.admin_master')
@section('title', 'Consultation Workspace')
@section('admin')
<div class="page-header">
    <h1>Consultation Workspace</h1>
    <p>{{ $visit->patient->full_name }} • {{ $visit->visit_number }}</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header"><h5 class="card-title">Triage Summary</h5></div>
            <div class="card-body">
                <p><strong>Complaint:</strong> {{ optional($visit->triageQueue?->assessment)->chief_complaints ?: $visit->chief_complaint ?: 'N/A' }}</p>
                <p><strong>BP:</strong> {{ optional($visit->triageQueue?->vitals)->blood_pressure ?: 'N/A' }}</p>
                <p><strong>Temp:</strong> {{ optional($visit->triageQueue?->vitals)->temperature ?: 'N/A' }}</p>
                <p><strong>Priority:</strong> {{ optional($visit->triageQueue?->assessment)->priority_level ?: 'Pending' }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header"><h5 class="card-title">Existing Orders</h5></div>
            <div class="card-body">
                <p><strong>Prescriptions:</strong> {{ $visit->prescriptions->count() }}</p>
                <p><strong>Service Orders:</strong> {{ $visit->serviceOrders->count() }}</p>
                <p><strong>Current Stage:</strong> {{ ucfirst($visit->current_stage) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><h5 class="card-title">SOAP / Diagnosis</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.doctor.encounter', $visit) }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Subjective</label><textarea class="form-control" rows="3" name="subjective">{{ old('subjective', $visit->encounter->subjective ?? '') }}</textarea></div>
                <div class="col-md-6"><label class="form-label">Objective</label><textarea class="form-control" rows="3" name="objective">{{ old('objective', $visit->encounter->objective ?? '') }}</textarea></div>
                <div class="col-md-6"><label class="form-label">Assessment</label><textarea class="form-control" rows="3" name="assessment">{{ old('assessment', $visit->encounter->assessment ?? '') }}</textarea></div>
                <div class="col-md-6"><label class="form-label">Plan</label><textarea class="form-control" rows="3" name="plan">{{ old('plan', $visit->encounter->plan ?? '') }}</textarea></div>
                <div class="col-md-4"><label class="form-label">ICD-10 Code</label><input class="form-control" name="icd10_code" value="{{ old('icd10_code', $visit->encounter->icd10_code ?? '') }}"></div>
                <div class="col-md-4"><label class="form-label">Diagnosis</label><input class="form-control" name="diagnosis" value="{{ old('diagnosis', $visit->encounter->diagnosis ?? '') }}"></div>
                <div class="col-md-4"><label class="form-label">Disposition</label>
                    <select class="form-select" name="disposition">
                        <option value="ongoing">Keep in Doctor</option>
                        <option value="nurse">Forward to Nurse</option>
                        <option value="pharmacy">Forward to Pharmacy</option>
                        <option value="lab">Forward to Laboratory</option>
                        <option value="radiology">Forward to Radiology</option>
                        <option value="discharged">Discharge</option>
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">Referral Notes</label><textarea class="form-control" rows="2" name="referral_notes">{{ old('referral_notes', $visit->encounter->referral_notes ?? '') }}</textarea></div>
                <div class="col-md-6"><label class="form-label">Discharge Notes</label><textarea class="form-control" rows="2" name="discharge_notes">{{ old('discharge_notes', $visit->encounter->discharge_notes ?? '') }}</textarea></div>
            </div>
            <button class="btn btn-primary mt-3" type="submit">Save Consultation</button>
        </form>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="card-title">Add Prescription</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.doctor.prescriptions', $visit) }}">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-6"><input class="form-control" name="drug_name" placeholder="Drug name"></div>
                        <div class="col-md-6"><input class="form-control" name="dosage" placeholder="Dosage"></div>
                        <div class="col-md-6"><input class="form-control" name="frequency" placeholder="Frequency"></div>
                        <div class="col-md-6"><input class="form-control" name="duration" placeholder="Duration"></div>
                        <div class="col-12"><textarea class="form-control" rows="2" name="instructions" placeholder="Instructions"></textarea></div>
                    </div>
                    <button class="btn btn-success mt-3" type="submit">Add Prescription</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h5 class="card-title">Order Lab / Imaging</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.doctor.service-orders', $visit) }}">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-5">
                            <select class="form-select" name="service_type">
                                <option value="laboratory">Laboratory</option>
                                <option value="radiology">Radiology</option>
                            </select>
                        </div>
                        <div class="col-md-7"><input class="form-control" name="request_name" placeholder="Requested investigation"></div>
                        <div class="col-12"><textarea class="form-control" rows="2" name="instructions" placeholder="Clinical notes / instructions"></textarea></div>
                    </div>
                    <button class="btn btn-warning mt-3" type="submit">Create Request</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
