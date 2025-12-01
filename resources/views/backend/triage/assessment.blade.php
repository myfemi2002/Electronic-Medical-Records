@extends('admin.admin_master')
@section('title', 'Triage Assessment')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Triage Assessment</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.triage.index') }}">Triage</a></li>
                    <li class="breadcrumb-item active">Assessment</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Patient Info -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fa fa-user"></i> Patient Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Card Number:</strong></p>
                            <p><span class="badge bg-primary">{{ $queue->patient->card_number }}</span></p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Full Name:</strong></p>
                            <p>{{ $queue->patient->full_name }}</p>
                        </div>
                        <div class="col-md-2">
                            <p class="mb-1"><strong>Gender / Age:</strong></p>
                            <p>{{ $queue->patient->gender }} / {{ $queue->patient->age }}yrs</p>
                        </div>
                        <div class="col-md-2">
                            <p class="mb-1"><strong>Queue Number:</strong></p>
                            <p><span class="badge bg-dark">{{ $queue->queue_number }}</span></p>
                        </div>
                        <div class="col-md-2">
                            <p class="mb-1"><strong>Priority:</strong></p>
                            <p>{!! $queue->priority_badge !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vital Signs & Interpretations -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-{{ $queue->priority === 'critical' ? 'danger' : ($queue->priority === 'moderate' ? 'warning' : 'success') }}">
                <div class="card-header bg-{{ $queue->priority === 'critical' ? 'danger' : ($queue->priority === 'moderate' ? 'warning' : 'success') }} text-white">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fa fa-heartbeat"></i> Vital Signs & Interpretation
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <strong>Blood Pressure:</strong>
                            <p class="mb-0">{{ $queue->vitals->blood_pressure }}</p>
                        </div>
                        <div class="col-md-10">
                            <span class="badge bg-{{ strpos(strtolower($queue->vitals->bp_interpretation), 'crisis') !== false || strpos(strtolower($queue->vitals->bp_interpretation), 'stage 2') !== false ? 'danger' : (strpos(strtolower($queue->vitals->bp_interpretation), 'stage 1') !== false ? 'warning' : 'success') }}">
                                {{ $queue->vitals->bp_interpretation }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <strong>Temperature:</strong>
                            <p class="mb-0">{{ $queue->vitals->temperature }}°C</p>
                        </div>
                        <div class="col-md-10">
                            <span class="badge bg-{{ strpos(strtolower($queue->vitals->temp_interpretation), 'hyper') !== false || strpos(strtolower($queue->vitals->temp_interpretation), 'hypo') !== false ? 'danger' : (strpos(strtolower($queue->vitals->temp_interpretation), 'fever') !== false ? 'warning' : 'success') }}">
                                {{ $queue->vitals->temp_interpretation }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <strong>Pulse Rate:</strong>
                            <p class="mb-0">{{ $queue->vitals->pulse_rate }} bpm</p>
                        </div>
                        <div class="col-md-10">
                            <span class="badge bg-{{ strpos(strtolower($queue->vitals->pulse_interpretation), 'severe') !== false ? 'danger' : (strpos(strtolower($queue->vitals->pulse_interpretation), 'tachy') !== false || strpos(strtolower($queue->vitals->pulse_interpretation), 'brady') !== false ? 'warning' : 'success') }}">
                                {{ $queue->vitals->pulse_interpretation }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <strong>Respiratory Rate:</strong>
                            <p class="mb-0">{{ $queue->vitals->respiratory_rate }}/min</p>
                        </div>
                        <div class="col-md-10">
                            <span class="badge bg-{{ strpos(strtolower($queue->vitals->rr_interpretation), 'severe') !== false ? 'danger' : (strpos(strtolower($queue->vitals->rr_interpretation), 'tachy') !== false || strpos(strtolower($queue->vitals->rr_interpretation), 'brady') !== false ? 'warning' : 'success') }}">
                                {{ $queue->vitals->rr_interpretation }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <strong>Oxygen Saturation:</strong>
                            <p class="mb-0">{{ $queue->vitals->oxygen_saturation }}%</p>
                        </div>
                        <div class="col-md-10">
                            <span class="badge bg-{{ strpos(strtolower($queue->vitals->spo2_interpretation), 'severe') !== false ? 'danger' : (strpos(strtolower($queue->vitals->spo2_interpretation), 'hypo') !== false ? 'warning' : 'success') }}">
                                {{ $queue->vitals->spo2_interpretation }}
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <strong>BMI:</strong>
                            <p class="mb-0">{{ $queue->vitals->bmi }}</p>
                        </div>
                        <div class="col-md-10">
                            <span class="badge bg-info">
                                {{ $queue->vitals->bmi_interpretation }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Clinical Alerts -->
    @if($queue->vitals->clinical_alerts && count($queue->vitals->clinical_alerts) > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-{{ $queue->priority === 'critical' ? 'danger' : 'warning' }} alert-dismissible fade show">
                <h5 class="alert-heading">
                    <i class="fa fa-exclamation-triangle"></i> Clinical Alerts
                </h5>
                <ul class="mb-0">
                    @foreach($queue->vitals->clinical_alerts as $alert)
                        <li>{{ $alert }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- System Suggestions -->
    @if(isset($analysis['suggestions']) && count($analysis['suggestions']) > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <h5 class="alert-heading">
                    <i class="fa fa-lightbulb"></i> System Recommendations
                </h5>
                <ul class="mb-0">
                    @foreach($analysis['suggestions'] as $suggestion)
                        <li>{{ $suggestion }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Assessment Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-notes-medical"></i> Clinical Assessment
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.triage.store-assessment', $queue->id) }}" method="POST">
                        @csrf

                        <!-- Chief Complaints -->
                        <div class="form-group">
                            <label for="chief_complaints">Chief Complaints <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('chief_complaints') is-invalid @enderror" 
                                      id="chief_complaints" 
                                      name="chief_complaints" 
                                      rows="3"
                                      placeholder="Main symptoms or reasons for visit..."
                                      required>{{ old('chief_complaints', $queue->initial_complaint) }}</textarea>
                            @error('chief_complaints')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- History of Present Illness -->
                        <div class="form-group">
                            <label for="history_of_present_illness">History of Present Illness</label>
                            <textarea class="form-control @error('history_of_present_illness') is-invalid @enderror" 
                                      id="history_of_present_illness" 
                                      name="history_of_present_illness" 
                                      rows="4"
                                      placeholder="When did symptoms start? How have they progressed?">{{ old('history_of_present_illness') }}</textarea>
                            @error('history_of_present_illness')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Priority Level -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priority_level">Priority Level <span class="text-danger">*</span></label>
                                    <select class="form-select @error('priority_level') is-invalid @enderror" 
                                            id="priority_level" 
                                            name="priority_level" 
                                            required>
                                        <option value="critical" {{ old('priority_level', $queue->priority) == 'critical' ? 'selected' : '' }}>
                                            Critical - Immediate attention required
                                        </option>
                                        <option value="moderate" {{ old('priority_level', $queue->priority) == 'moderate' ? 'selected' : '' }}>
                                            Moderate - Prompt attention needed
                                        </option>
                                        <option value="mild" {{ old('priority_level', $queue->priority) == 'mild' ? 'selected' : '' }}>
                                            Mild - Standard care
                                        </option>
                                    </select>
                                    @error('priority_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Initial Assessment Notes -->
                        <div class="form-group">
                            <label for="initial_assessment_notes">Initial Assessment Notes</label>
                            <textarea class="form-control @error('initial_assessment_notes') is-invalid @enderror" 
                                      id="initial_assessment_notes" 
                                      name="initial_assessment_notes" 
                                      rows="3"
                                      placeholder="Clinical observations and initial impressions...">{{ old('initial_assessment_notes') }}</textarea>
                            @error('initial_assessment_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nurse Notes -->
                        <div class="form-group">
                            <label for="nurse_notes">Nurse Notes</label>
                            <textarea class="form-control @error('nurse_notes') is-invalid @enderror" 
                                      id="nurse_notes" 
                                      name="nurse_notes" 
                                      rows="3"
                                      placeholder="Additional observations, patient behavior, concerns...">{{ old('nurse_notes', $queue->vitals->notes) }}</textarea>
                            @error('nurse_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Forwarding Section -->
                        <div class="card border-warning mt-4">
                            <div class="card-header bg-warning">
                                <h5 class="card-title mb-0">
                                    <i class="fa fa-arrow-right"></i> Forward Patient
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Forward to Department -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="forwarded_to_department_id">Forward to Department <span class="text-danger">*</span></label>
                                            <select class="form-select @error('forwarded_to_department_id') is-invalid @enderror" 
                                                    id="forwarded_to_department_id" 
                                                    name="forwarded_to_department_id" 
                                                    required>
                                                <option value="">Select Department</option>
                                                @foreach($departments as $dept)
                                                    <option value="{{ $dept->id }}" {{ old('forwarded_to_department_id') == $dept->id ? 'selected' : '' }}>
                                                        {{ $dept->name }}
                                                        @if($dept->location) - {{ $dept->location }}@endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('forwarded_to_department_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Forward to Staff (Optional) -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="forwarded_to_staff_id">Forward to Specific Staff (Optional)</label>
                                            <select class="form-select" 
                                                    id="forwarded_to_staff_id" 
                                                    name="forwarded_to_staff_id">
                                                <option value="">Any available staff</option>
                                            </select>
                                            <small class="text-muted">Staff list will load when department is selected</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Forwarding Reason -->
                                <div class="form-group">
                                    <label for="forwarding_reason">Forwarding Reason <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('forwarding_reason') is-invalid @enderror" 
                                              id="forwarding_reason" 
                                              name="forwarding_reason" 
                                              rows="2"
                                              placeholder="Why is this patient being forwarded to this department?"
                                              required>{{ old('forwarding_reason') }}</textarea>
                                    @error('forwarding_reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-save"></i> Complete Assessment & Forward
                            </button>
                            <a href="{{ route('admin.triage.waiting-list') }}" class="btn btn-secondary btn-lg">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
$(document).ready(function() {
    // Load staff when department is selected
    $('#forwarded_to_department_id').change(function() {
        const departmentId = $(this).val();
        const staffSelect = $('#forwarded_to_staff_id');
        
        staffSelect.html('<option value="">Loading...</option>');
        
        if (departmentId) {
            $.ajax({
                url: '/triage/get-department-staff/' + departmentId,
                type: 'GET',
                success: function(staff) {
                    staffSelect.html('<option value="">Any available staff</option>');
                    
                    if (staff.length > 0) {
                        staff.forEach(function(member) {
                            const staffType = member.staff_type ? ' (' + member.staff_type + ')' : '';
                            staffSelect.append(
                                '<option value="' + member.id + '">' + 
                                member.name + staffType +
                                '</option>'
                            );
                        });
                    } else {
                        staffSelect.append('<option value="">No staff available</option>');
                    }
                },
                error: function() {
                    staffSelect.html('<option value="">Error loading staff</option>');
                }
            });
        } else {
            staffSelect.html('<option value="">Select department first</option>');
        }
    });
});
</script>
@endpush

@endsection