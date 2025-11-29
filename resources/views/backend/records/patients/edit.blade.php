{{-- resources/views/backend/records/patients/edit.blade.php --}}
@extends('admin.admin_master')
@section('title', 'Edit Patient')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h3 class="page-title">Edit Patient Information</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.records.patients.index') }}">Patient Registration</a></li>
                    <li class="breadcrumb-item active">Edit Patient</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Update Patient Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.records.patients.update', $patient->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12">
                                
                                <h4 class="card-title mb-4">Personal Details</h4>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Card Number <span class="text-danger">*</span></label>
                                            <input type="text" name="card_number" class="form-control" style="text-transform: uppercase" value="{{ old('card_number', $patient->card_number) }}" required>
                                            @error('card_number')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Last Name (Surname) <span class="text-danger">*</span></label>
                                            <input type="text" name="patient_lastname" class="form-control" value="{{ old('patient_lastname', $patient->patient_lastname) }}" required>
                                            @error('patient_lastname')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="patient_firstname" class="form-control" value="{{ old('patient_firstname', $patient->patient_firstname) }}" required>
                                            @error('patient_firstname')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Phone No <span class="text-danger">*</span></label>
                                            <input type="number" name="patient_phone" min="1" class="form-control" value="{{ old('patient_phone', $patient->patient_phone) }}" required>
                                            @error('patient_phone')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Occupation</label>
                                            <input type="text" name="patient_occupation" class="form-control" value="{{ old('patient_occupation', $patient->patient_occupation) }}">
                                            @error('patient_occupation')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Religion <span class="text-danger">*</span></label>
                                            <select name="patient_religion" class="form-select" required>
                                                <option value="" disabled>-- Select --</option>
                                                <option value="Christianity" {{ old('patient_religion', $patient->patient_religion) == 'Christianity' ? 'selected' : '' }}>Christianity</option>
                                                <option value="Islam" {{ old('patient_religion', $patient->patient_religion) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                                <option value="Indigenous" {{ old('patient_religion', $patient->patient_religion) == 'Indigenous' ? 'selected' : '' }}>Indigenous</option>
                                                <option value="Indo" {{ old('patient_religion', $patient->patient_religion) == 'Indo' ? 'selected' : '' }}>Indo</option>
                                            </select>
                                            @error('patient_religion')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Gender <span class="text-danger">*</span></label>
                                            <select name="patient_gender" class="form-select" required>
                                                <option value="" disabled>-- Select --</option>
                                                <option value="Male" {{ old('patient_gender', $patient->patient_gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('patient_gender', $patient->patient_gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                            @error('patient_gender')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Status <span class="text-danger">*</span></label>
                                            <select name="patient_status" class="form-select" required>
                                                <option value="" disabled>-- Select --</option>
                                                <option value="Single" {{ old('patient_status', $patient->patient_status) == 'Single' ? 'selected' : '' }}>Single</option>
                                                <option value="Married" {{ old('patient_status', $patient->patient_status) == 'Married' ? 'selected' : '' }}>Married</option>
                                                <option value="Divored" {{ old('patient_status', $patient->patient_status) == 'Divored' ? 'selected' : '' }}>Divored</option>
                                                <option value="Widowed" {{ old('patient_status', $patient->patient_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                            </select>
                                            @error('patient_status')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Date Of Birth <span class="text-danger">*</span></label>
                                            <input type="date" name="patient_dob" class="form-control" value="{{ old('patient_dob', $patient->patient_dob) }}" required>
                                            @error('patient_dob')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Type Of Patient <span class="text-danger">*</span></label>
                                            <select name="patient_type" class="form-select" id="patientType" required>
                                                <option value="" disabled>-- Select --</option>
                                                <option value="Private" {{ old('patient_type', $patient->patient_type) == 'Private' ? 'selected' : '' }}>Private</option>
                                                <option value="HMO" {{ old('patient_type', $patient->patient_type) == 'HMO' ? 'selected' : '' }}>HMO</option>
                                                <option value="Retainership" {{ old('patient_type', $patient->patient_type) == 'Retainership' ? 'selected' : '' }}>Retainership</option>
                                                <option value="Pre-Medical" {{ old('patient_type', $patient->patient_type) == 'Pre-Medical' ? 'selected' : '' }}>Pre-Medical</option>
                                            </select>
                                            @error('patient_type')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4" id="hmoField" style="display: {{ old('patient_type', $patient->patient_type) == 'HMO' ? 'block' : 'none' }};">
                                        <div class="form-group mb-3">
                                            <label>Type Of HMO</label>
                                            <div class="input-group">
                                                <select name="patient_hmo" class="form-select">
                                                    <option value="" disabled>-- Select --</option>
                                                    @foreach ($hmorg as $hmogs)
                                                    <option value="{{ $hmogs->hmo_name }}" {{ old('patient_hmo', $patient->patient_hmo) == $hmogs->hmo_name ? 'selected' : '' }}>{{ $hmogs->hmo_name }}</option>
                                                    @endforeach
                                                </select>
                                                <a href="{{ route('admin.settings.hmo.index') }}" class="btn btn-outline-secondary" title="Manage HMO">
                                                    <i class="bi bi-gear"></i>
                                                </a>
                                            </div>
                                            @error('patient_hmo')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Nationality</label>
                                            <input type="text" name="patient_nationality" class="form-control" value="{{ old('patient_nationality', $patient->patient_nationality) }}">
                                            @error('patient_nationality')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Address</label>
                                            <textarea rows="2" cols="5" name="patient_address" class="form-control" placeholder="Enter patient address">{{ old('patient_address', $patient->patient_address) }}</textarea>
                                            @error('patient_address')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label>Upload Patient Document <small>(Optional)</small></label>
                                            <input type="file" name="image" class="form-control" accept="image/*">
                                            @if($patient->image)
                                                <small class="text-muted">Current: {{ basename($patient->image) }}</small>
                                            @endif
                                            @error('image')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <h4 class="card-title mb-4 mt-4">Next Of Kin Details</h4>
                                
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label>Next Of Kin Name</label>
                                            <input type="text" name="patient_kin_name" class="form-control" value="{{ old('patient_kin_name', $patient->patient_kin_name) }}">
                                            @error('patient_kin_name')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label>Relationship</label>
                                            <input type="text" name="kin_relationship" class="form-control" value="{{ old('kin_relationship', $patient->kin_relationship) }}">
                                            @error('kin_relationship')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label>Next Of Kin Phone No</label>
                                            <input type="number" name="patient_kin_phone" min="1" class="form-control" value="{{ old('patient_kin_phone', $patient->patient_kin_phone) }}">
                                            @error('patient_kin_phone')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label>Next Of Kin Address</label>
                                            <textarea rows="2" cols="5" name="patient_kin_address" class="form-control" placeholder="Enter next of kin address">{{ old('patient_kin_address', $patient->patient_kin_address) }}</textarea>
                                            @error('patient_kin_address')
                                            <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end mt-4">
                            <a href="{{ route('admin.records.patients.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Show/Hide HMO field based on patient type
    document.getElementById('patientType').addEventListener('change', function() {
        const hmoField = document.getElementById('hmoField');
        if (this.value === 'HMO') {
            hmoField.style.display = 'block';
        } else {
            hmoField.style.display = 'none';
        }
    });
</script>
@endpush