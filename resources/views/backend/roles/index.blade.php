@extends('admin.admin_master')
@section('title', 'Manage Roles')
@section('admin')



<div class="card bg-white border-0 rounded-3 mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <h3 class="mb-0">Role List</h3>
            
        <div class="col-auto float-end ms-auto">
            <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_role">
                <i class="fa-solid fa-plus"></i> Add Role
            </a>
        </div>
        </div>

        <div class="default-table-area">
            <div class="table-responsive">
                <table class="table align-middle custom-table mb-0 datatable">
                    <thead>
                        <tr>
                            <th style="width: 30px;">#</th>
                            <th>Role Name</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allData as $key => $role)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $role->name }}</td>
                            <td class="text-end">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <a href="#" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" 
                                       data-bs-target="#edit_role_{{ $role->id }}">
                                        <i class="fa-solid fa-pencil"></i>
                                    </a>
                                    <a href="{{ route('roles.delete', $role->id) }}" 
                                       class="btn btn-sm btn-danger" id="delete">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Role Modal -->
                        <div id="edit_role_{{ $role->id }}" class="modal custom-modal fade" role="dialog">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Role</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" 
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('roles.update', $role->id) }}" method="POST">
                                            @csrf
                                            <div class="input-block mb-3">
                                                <label class="col-form-label">Role Name 
                                                    <span class="text-danger">*</span></label>
                                                <input class="form-control" name="name" 
                                                       value="{{ $role->name }}" type="text" required>
                                                <small class="form-control-feedback">
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </small>
                                            </div>
                                            <div class="submit-section">
                                                <button type="button" class="btn btn-secondary me-2" 
                                                        data-bs-dismiss="modal">Close</button>
                                                <button class="btn btn-primary submit-btn">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Role Modal -->
<div id="add_role" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <div class="input-block mb-3">
                        <label class="col-form-label">Role Name <span class="text-danger">*</span></label>
                        <input class="form-control" name="name" type="text" required>
                        <small class="form-control-feedback">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </small>
                    </div>
                    <div class="submit-section">
                        <button type="button" class="btn btn-secondary me-2" 
                                data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary submit-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.datatable').DataTable({
        "order": [[0, "desc"]]
    });
});
</script>
@endpush

@push('styles')
<style>
.custom-table {
    background: #fff;
}
.table th {
    white-space: nowrap;
}
</style>
@endpush