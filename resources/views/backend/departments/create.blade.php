@extends('admin.admin_master')
@section('title', isset($department) ? 'Edit Department' : 'Create Department')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">{{ isset($department) ? 'Edit Department' : 'Create New Department' }}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.departments.index') }}">Departments</a></li>
                    <li class="breadcrumb-item active">{{ isset($department) ? 'Edit' : 'Create' }}</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Department Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-building"></i> Department Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ isset($department) ? route('admin.departments.update', $department->id) : route('admin.departments.store') }}" 
                          method="POST">
                        @csrf
                        @if(isset($department))
                            @method('POST')
                        @endif

                        <div class="row">
                            <!-- Department Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Department Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $department->name ?? '') }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Department Code -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="code">Code <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control text-uppercase @error('code') is-invalid @enderror" 
                                           id="code" 
                                           name="code" 
                                           value="{{ old('code', $department->code ?? '') }}" 
                                           maxlength="10" 
                                           required>
                                    <small class="text-muted">e.g., TRG, EMG, PHM</small>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Department Type -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="type">Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" 
                                            name="type" 
                                            required>
                                        <option value="">Select Type</option>
                                        <option value="medical" {{ old('type', $department->type ?? '') == 'medical' ? 'selected' : '' }}>
                                            Medical
                                        </option>
                                        <option value="support" {{ old('type', $department->type ?? '') == 'support' ? 'selected' : '' }}>
                                            Support
                                        </option>
                                        <option value="administrative" {{ old('type', $department->type ?? '') == 'administrative' ? 'selected' : '' }}>
                                            Administrative
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3">{{ old('description', $department->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Location -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <input type="text" 
                                           class="form-control @error('location') is-invalid @enderror" 
                                           id="location" 
                                           name="location" 
                                           value="{{ old('location', $department->location ?? '') }}" 
                                           placeholder="e.g., Building A, Floor 2">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $department->phone ?? '') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $department->email ?? '') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Head of Department -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="head_of_department_id">Head of Department</label>
                                    <select class="form-select @error('head_of_department_id') is-invalid @enderror" 
                                            id="head_of_department_id" 
                                            name="head_of_department_id">
                                        <option value="">Select Head</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" 
                                                {{ old('head_of_department_id', $department->head_of_department_id ?? '') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                                @if($user->staff_type)
                                                    ({{ ucfirst($user->staff_type) }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('head_of_department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Display Order -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="display_order">Display Order</label>
                                    <input type="number" 
                                           class="form-control @error('display_order') is-invalid @enderror" 
                                           id="display_order" 
                                           name="display_order" 
                                           value="{{ old('display_order', $department->display_order ?? 0) }}" 
                                           min="0">
                                    @error('display_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status (only for edit) -->
                            @if(isset($department))
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active" {{ old('status', $department->status) == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="inactive" {{ old('status', $department->status) == 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Checkboxes -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="can_receive_triage_patients" 
                                           name="can_receive_triage_patients" 
                                           value="1"
                                           {{ old('can_receive_triage_patients', $department->can_receive_triage_patients ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="can_receive_triage_patients">
                                        <i class="fa fa-stethoscope"></i> Can receive triage patients
                                    </label>
                                    <small class="form-text text-muted d-block">
                                        If enabled, this department will appear in triage forwarding options
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="requires_appointment" 
                                           name="requires_appointment" 
                                           value="1"
                                           {{ old('requires_appointment', $department->requires_appointment ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="requires_appointment">
                                        <i class="fa fa-calendar"></i> Requires appointment
                                    </label>
                                    <small class="form-text text-muted d-block">
                                        Patients must book appointments to visit this department
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> {{ isset($department) ? 'Update Department' : 'Create Department' }}
                            </button>
                            <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection