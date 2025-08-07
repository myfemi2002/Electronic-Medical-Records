@extends('admin.admin_master')
@section('title', 'Roles & Permissions Management')
@section('admin')

<!-- Required CSS -->
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">

<!-- Page Header -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-auto float-end ms-auto">
            <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_role">
                <i class="fa-solid fa-plus"></i> Add New Role
            </a>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped custom-table mb-0 datatable">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Role Name</th>
                                <th width="30%">Permissions</th>
                                <th width="20%" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $key => $role)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $role->name }}</td>
                                
                                <td>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" 
                                        data-bs-target="#view_permissions_{{ $role->id }}">
                                        <i class="fa-solid fa-eye"></i> View Permissions ({{ $role->permissions->count() }})
                                    </button>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" 
                                        data-bs-target="#edit_role_{{ $role->id }}">
                                        <i class="fa-solid fa-pencil"></i>
                                    </button>
                                    @if($role->name !== 'Super Admin')
                                    <button class="btn btn-sm btn-danger delete-role" 
                                        data-id="{{ $role->id }}" 
                                        data-name="{{ $role->name }}">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>

                            <!-- View Permissions Modal -->
                            <div class="modal fade" id="view_permissions_{{ $role->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ $role->name }} - Current Permissions</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            @foreach($permission_groups as $group)
                                            @php
                                                $groupPermissions = $role->permissions->where('group_name', $group->group_name);
                                            @endphp
                                            @if($groupPermissions->count() > 0)
                                            <div class="permission-group mb-4">
                                                <h6 class="text-primary mb-3">{{ ucfirst($group->group_name) }}</h6>
                                                <div class="row g-2">
                                                    @foreach($groupPermissions as $permission)
                                                    <div class="col-md-4">
                                                        <span class="badge bg-primary rounded-pill">
                                                            {{ $permission->sidebar_name }}
                                                        </span>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Role Modal -->
                            <div class="modal fade" id="edit_role_{{ $role->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Role: {{ $role->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('roleswithpermission.update', $role->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Role Name</label>
                                                            <input type="text" class="form-control" name="name" 
                                                                value="{{ $role->name }}" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="permissions-section">
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <h6 class="mb-0">Permissions</h6>
                                                        <button type="button" class="btn btn-primary btn-sm select-all-btn">
                                                            <i class="fa-regular fa-square-check me-1"></i> Select All
                                                        </button>
                                                    </div>

                                                    @foreach($permission_groups as $group)
                                                    <div class="permission-group card mb-3">
                                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                                            <h6 class="mb-0">{{ ucfirst($group->group_name) }}</h6>
                                                            <button type="button" class="btn btn-outline-primary btn-sm group-select-btn">
                                                                Select Group
                                                            </button>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-3">
                                                                @php
                                                                    $permissions = App\Models\User::getPermissionByGroupName($group->group_name);
                                                                @endphp
                                                                @foreach($permissions as $permission)
                                                                <div class="col-md-4">
                                                                    <div class="form-check permission-item">
                                                                        <input class="form-check-input permission-checkbox" 
                                                                            type="checkbox"
                                                                            name="permissions[]" 
                                                                            value="{{ $permission->id }}"
                                                                            id="edit_perm_{{ $role->id }}_{{ $permission->id }}"
                                                                            {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                                                        <label class="form-check-label" 
                                                                            for="edit_perm_{{ $role->id }}_{{ $permission->id }}">
                                                                            {{ $permission->sidebar_name }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Update Permissions</button>
                                            </div>
                                        </form>
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
</div>

<!-- Add Role Modal -->
<div class="modal fade" id="add_role" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('roleswithpermission.store') }}" method="POST" id="add_role_form">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Role Name <span class="text-danger">*</span></label>
                                <select class="form-select" name="role_id" required>
                                    <option value="">-- Select Role --</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="permissions-section">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Permissions</h6>
                            <button type="button" class="btn btn-primary btn-sm select-all-btn">
                                <i class="fa-regular fa-square-check me-1"></i> Select All
                            </button>
                        </div>

                        @foreach($permission_groups as $group)
                        <div class="permission-group card mb-3">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">{{ ucfirst($group->group_name) }}</h6>
                                <button type="button" class="btn btn-outline-primary btn-sm group-select-btn">
                                    Select Group
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @php
                                        $permissions = App\Models\User::getPermissionByGroupName($group->group_name);
                                    @endphp
                                    @foreach($permissions as $permission)
                                    <div class="col-md-4">
                                        <div class="form-check permission-item">
                                            <input class="form-check-input permission-checkbox" 
                                                type="checkbox"
                                                name="permissions[]" 
                                                value="{{ $permission->id }}"
                                                id="new_perm_{{ $permission->id }}">
                                            <label class="form-check-label" for="new_perm_{{ $permission->id }}">
                                                {{ $permission->sidebar_name }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .permission-group {
        transition: all 0.3s ease;
    }
    .permission-group:hover {
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .form-check-label {
        font-size: 0.9rem;
        cursor: pointer;
    }
    .group-select-btn {
        font-size: 0.8rem;
        padding: 0.25rem 0.75rem;
    }
    .badge {
        font-weight: 500;
        font-size: 0.85rem;
    }
    .form-check-input:checked + .form-check-label {
        color: #0d6efd;
        font-weight: 500;
    }
    .modal-lg {
        max-width: 800px;
    }
    .modal-body {
        max-height: calc(100vh - 210px);
        overflow-y: auto;
    }
</style>

<!-- Required Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {


    // Update permission count for a modal
    function updatePermissionCount(modal) {
        const total = modal.find('.permission-checkbox').length;
        const checked = modal.find('.permission-checkbox:checked').length;
        modal.find('.permission-count').text(`Selected Permissions: ${checked} of ${total}`);
    }

    // Update group select button text
    function updateGroupSelectButton(group) {
        const checkboxes = group.find('.permission-checkbox');
        const checkedBoxes = group.find('.permission-checkbox:checked');
        const button = group.find('.group-select-btn');
        
        if (checkboxes.length === checkedBoxes.length) {
            button.text('Unselect Group');
        } else {
            button.text('Select Group');
        }
    }

    // Handle "Select All" button click
    $(document).on('click', '.select-all-btn', function(e) {
        e.preventDefault();
        const modal = $(this).closest('.modal');
        const checkboxes = modal.find('.permission-checkbox');
        const allChecked = checkboxes.length === modal.find('.permission-checkbox:checked').length;
        
        // Toggle all checkboxes
        checkboxes.prop('checked', !allChecked);
        
        // Update all group buttons
        modal.find('.permission-group').each(function() {
            updateGroupSelectButton($(this));
        });
        
        // Update permission count
        updatePermissionCount(modal);
    });

    // Handle "Select Group" button click
    $(document).on('click', '.group-select-btn', function(e) {
        e.preventDefault();
        const group = $(this).closest('.permission-group');
        const checkboxes = group.find('.permission-checkbox');
        const allChecked = checkboxes.length === group.find('.permission-checkbox:checked').length;
        
        // Toggle checkboxes in this group
        checkboxes.prop('checked', !allChecked);
        
        // Update this group's button
        updateGroupSelectButton(group);
        
        // Update permission count
        updatePermissionCount($(this).closest('.modal'));
    });

    // Handle individual checkbox changes
    $(document).on('change', '.permission-checkbox', function() {
        const modal = $(this).closest('.modal');
        const group = $(this).closest('.permission-group');
        
        // Update this group's button
        updateGroupSelectButton(group);
        
        // Update permission count
        updatePermissionCount(modal);
    });

    // Initialize on page load
    $('.modal').each(function() {
        const modal = $(this);
        updatePermissionCount(modal);
        modal.find('.permission-group').each(function() {
            updateGroupSelectButton($(this));
        });
    });

    // Form submission handler
    $('.modal form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const modal = form.closest('.modal');
        
        // Validate permissions selection
        if (modal.find('.permission-checkbox:checked').length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please select at least one permission'
            });
            return false;
        }

        // Show loading state
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...').prop('disabled', true);

        // Submit form via AJAX
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Permissions updated successfully',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            },
            error: function(xhr) {
                submitBtn.html(originalText).prop('disabled', false);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'An error occurred while updating permissions'
                });
            }
        });
    });

    // Delete role handler
    $(document).on('click', '.delete-role', function() {
        const roleId = $(this).data('id');
        const roleName = $(this).data('name');

        Swal.fire({
            title: 'Are you sure?',
            text: `Delete role: ${roleName}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/roleswithpermission/delete/${roleId}`;
            }
        });
    });
});
</script>

@endsection