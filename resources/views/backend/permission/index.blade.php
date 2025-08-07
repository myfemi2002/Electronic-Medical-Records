@extends('admin.admin_master')
@section('title', 'Manage Permissions')
@section('admin')

<div class="card bg-white border-0 rounded-3 mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <h3 class="mb-0">Permission List</h3>
            
        <div class="col-auto float-end ms-auto">
            <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_permission">
                <i class="fa-solid fa-plus"></i> Add Permission
            </a>
        </div>
        </div>

        <div class="default-table-area">
            <div class="table-responsive">
                <table class="table align-middle custom-table mb-0 datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Permission Name</th>
                            <th>Group Name</th>
                            <th>Sidebar Name</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allData as $key => $permission)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $permission->name }}</td>
                            <td>{{ $permission->group_name }}</td>
                            <td>{{ $permission->sidebar_name }}</td>
                            <td class="text-end">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <a href="#" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" 
                                       data-bs-target="#edit_permission_{{ $permission->id }}">
                                        <i class="fa-solid fa-pencil"></i>
                                    </a>
                                    <a href="{{ route('permission.delete', $permission->id) }}" 
                                       class="btn btn-sm btn-danger" id="delete">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Permission Modal -->
                        <div id="edit_permission_{{ $permission->id }}" class="modal custom-modal fade" role="dialog">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Permission</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" 
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('permission.update', $permission->id) }}" method="POST">
                                            @csrf
                                            <div class="input-block mb-3">
                                                <label class="col-form-label">Permission Name 
                                                    <span class="text-danger">*</span></label>
                                                <input class="form-control" name="name" 
                                                       value="{{ $permission->name }}" type="text" required>
                                                <small class="form-control-feedback">
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </small>
                                            </div>
                                            <div class="input-block mb-3">
                                                <label class="col-form-label">Group Name 
                                                    <span class="text-danger">*</span></label>
                                                <select name="group_name" class="form-control" required>
                                                    <option value="">Select Group</option>
                                                    @foreach($groupname as $group)
                                                        <option value="{{ $group->group_name }}" 
                                                                {{ $permission->group_name == $group->group_name ? 'selected' : '' }}>
                                                            {{ $group->group_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="form-control-feedback">
                                                    @error('group_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </small>
                                            </div>
                                            <div class="input-block mb-3">
                                                <label class="col-form-label">Sidebar Name 
                                                    <span class="text-danger">*</span></label>
                                                <input class="form-control" name="sidebar_name" 
                                                       value="{{ $permission->sidebar_name }}" type="text" required>
                                                <small class="form-control-feedback">
                                                    @error('sidebar_name')
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

<!-- Add Permission Modal -->
<div id="add_permission" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('permission.store') }}" method="POST">
                    @csrf
                    <div class="input-block mb-3">
                        <label class="col-form-label">Permission Name <span class="text-danger">*</span></label>
                        <input class="form-control" name="name" type="text" required>
                        <small class="form-control-feedback">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </small>
                    </div>
                    <div class="input-block mb-3">
                        <label class="col-form-label">Group Name <span class="text-danger">*</span></label>
                        <select name="group_name" class="form-control" required>
                            <option value="">Select Group</option>
                            @foreach($groupname as $group)
                                <option value="{{ $group->group_name }}">{{ $group->group_name }}</option>
                            @endforeach
                        </select>
                        <small class="form-control-feedback">
                            @error('group_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </small>
                    </div>
                    <div class="input-block mb-3">
                        <label class="col-form-label">Sidebar Name <span class="text-danger">*</span></label>
                        <input class="form-control" name="sidebar_name" type="text" required>
                        <small class="form-control-feedback">
                            @error('sidebar_name')
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