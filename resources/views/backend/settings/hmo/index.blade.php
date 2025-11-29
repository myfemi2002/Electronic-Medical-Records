{{-- resources/views/backend/settings/hmo/index.blade.php --}}
@extends('admin.admin_master')
@section('title', 'HMO Management')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h3 class="page-title">HMO Management</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active">HMO Management</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">All HMO Providers</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHmoModal">
                        <i class="bi bi-plus-circle me-2"></i> Add New HMO
                    </button>
                </div>
                <div class="card-body">
                    <!-- HMO Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>HMO Name</th>
                                    <th>Contact Person</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hmos as $key => $hmo)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><strong>{{ $hmo->hmo_name }}</strong></td>
                                    <td>{{ $hmo->contact_person ?? 'N/A' }}</td>
                                    <td>{{ $hmo->phone ?? 'N/A' }}</td>
                                    <td>{{ $hmo->email ?? 'N/A' }}</td>
                                    <td>
                                        @if($hmo->status == 'Active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editHmoModal{{ $hmo->id }}" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <a href="{{ route('admin.settings.hmo.delete', $hmo->id) }}" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this HMO?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit HMO Modal -->
                                <div class="modal fade" id="editHmoModal{{ $hmo->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit HMO Provider</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="{{ route('admin.settings.hmo.update', $hmo->id) }}">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group mb-3">
                                                        <label>HMO Name <span class="text-danger">*</span></label>
                                                        <input type="text" name="hmo_name" class="form-control" value="{{ $hmo->hmo_name }}" required>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label>Contact Person</label>
                                                        <input type="text" name="contact_person" class="form-control" value="{{ $hmo->contact_person }}">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label>Phone</label>
                                                        <input type="text" name="phone" class="form-control" value="{{ $hmo->phone }}">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label>Email</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $hmo->email }}">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label>Address</label>
                                                        <textarea name="address" class="form-control" rows="2">{{ $hmo->address }}</textarea>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label>Status <span class="text-danger">*</span></label>
                                                        <select name="status" class="form-select" required>
                                                            <option value="Active" {{ $hmo->status == 'Active' ? 'selected' : '' }}>Active</option>
                                                            <option value="Inactive" {{ $hmo->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update HMO</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                        <p class="text-muted mt-2">No HMO providers added yet</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $hmos->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add HMO Modal -->
<div class="modal fade" id="addHmoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New HMO Provider</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.settings.hmo.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>HMO Name <span class="text-danger">*</span></label>
                        <input type="text" name="hmo_name" class="form-control" value="{{ old('hmo_name') }}" required>
                        @error('hmo_name')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label>Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="Active" selected>Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add HMO</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection