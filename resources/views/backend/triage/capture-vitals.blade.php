@extends('admin.admin_master')
@section('title', 'Capture Vital Signs')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Capture Vital Signs</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.triage.index') }}">Triage</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.triage.waiting-list') }}">Waiting List</a></li>
                    <li class="breadcrumb-item active">Capture Vitals</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Patient Info Card -->
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
                            <p class="mb-1"><strong>Gender:</strong></p>
                            <p>{{ $queue->patient->gender }}</p>
                        </div>
                        <div class="col-md-2">
                            <p class="mb-1"><strong>Age:</strong></p>
                            <p>{{ $queue->patient->age }} years</p>
                        </div>
                        <div class="col-md-2">
                            <p class="mb-1"><strong>Queue Number:</strong></p>
                            <p><span class="badge bg-dark">{{ $queue->queue_number }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vital Signs Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-heartbeat"></i> Vital Signs
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.triage.store-vitals', $queue->id) }}" method="POST">
                        @csrf

                        <div class="row">
                            <!-- Blood Pressure -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="blood_pressure">
                                        Blood Pressure (mmHg) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('blood_pressure') is-invalid @enderror" 
                                           id="blood_pressure" 
                                           name="blood_pressure" 
                                           placeholder="120/80"
                                           value="{{ old('blood_pressure') }}"
                                           required>
                                    <small class="text-muted">Format: Systolic/Diastolic (e.g., 120/80)</small>
                                    @error('blood_pressure')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Temperature -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="temperature">
                                        Temperature (°C) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('temperature') is-invalid @enderror" 
                                           id="temperature" 
                                           name="temperature" 
                                           step="0.1" 
                                           min="30" 
                                           max="45"
                                           placeholder="36.5"
                                           value="{{ old('temperature') }}"
                                           required>
                                    <small class="text-muted">Normal: 36.1 - 37.2°C</small>
                                    @error('temperature')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Pulse Rate -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pulse_rate">
                                        Pulse Rate (bpm) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('pulse_rate') is-invalid @enderror" 
                                           id="pulse_rate" 
                                           name="pulse_rate" 
                                           min="30" 
                                           max="200"
                                           placeholder="72"
                                           value="{{ old('pulse_rate') }}"
                                           required>
                                    <small class="text-muted">Normal: 60 - 100 bpm</small>
                                    @error('pulse_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Respiratory Rate -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="respiratory_rate">
                                        Respiratory Rate (breaths/min) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('respiratory_rate') is-invalid @enderror" 
                                           id="respiratory_rate" 
                                           name="respiratory_rate" 
                                           min="5" 
                                           max="60"
                                           placeholder="16"
                                           value="{{ old('respiratory_rate') }}"
                                           required>
                                    <small class="text-muted">Normal: 12 - 20 breaths/min</small>
                                    @error('respiratory_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Oxygen Saturation -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="oxygen_saturation">
                                        Oxygen Saturation (%) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('oxygen_saturation') is-invalid @enderror" 
                                           id="oxygen_saturation" 
                                           name="oxygen_saturation" 
                                           min="70" 
                                           max="100"
                                           placeholder="98"
                                           value="{{ old('oxygen_saturation') }}"
                                           required>
                                    <small class="text-muted">Normal: ≥ 95%</small>
                                    @error('oxygen_saturation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Weight -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="weight">
                                        Weight (kg) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('weight') is-invalid @enderror" 
                                           id="weight" 
                                           name="weight" 
                                           step="0.1" 
                                           min="1" 
                                           max="300"
                                           placeholder="70.5"
                                           value="{{ old('weight') }}"
                                           required>
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Height -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="height">
                                        Height (cm) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('height') is-invalid @enderror" 
                                           id="height" 
                                           name="height" 
                                           step="0.1" 
                                           min="30" 
                                           max="250"
                                           placeholder="170"
                                           value="{{ old('height') }}"
                                           required>
                                    @error('height')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- BMI (Auto-calculated) -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>BMI (Auto-calculated)</label>
                                    <input type="text" 
                                           class="form-control bg-light" 
                                           id="bmi_display" 
                                           readonly 
                                           placeholder="Will be calculated">
                                    <small class="text-muted">Normal: 18.5 - 24.9</small>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="form-group">
                            <label for="notes">Additional Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3"
                                      placeholder="Any observations or patient complaints...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-save"></i> Save & Analyze Vitals
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
    // Auto-calculate BMI
    function calculateBMI() {
        const weight = parseFloat($('#weight').val());
        const height = parseFloat($('#height').val());
        
        if (weight && height) {
            const heightInMeters = height / 100;
            const bmi = weight / (heightInMeters * heightInMeters);
            $('#bmi_display').val(bmi.toFixed(2));
            
            // Add color coding
            if (bmi < 18.5) {
                $('#bmi_display').removeClass().addClass('form-control bg-warning');
            } else if (bmi >= 18.5 && bmi <= 24.9) {
                $('#bmi_display').removeClass().addClass('form-control bg-success text-white');
            } else if (bmi >= 25 && bmi <= 29.9) {
                $('#bmi_display').removeClass().addClass('form-control bg-warning');
            } else {
                $('#bmi_display').removeClass().addClass('form-control bg-danger text-white');
            }
        }
    }
    
    $('#weight, #height').on('input', calculateBMI);
});
</script>
@endpush

@endsection