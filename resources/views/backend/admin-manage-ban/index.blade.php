@extends('admin.admin_master')
@section('title', 'Admin Manage Ban')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Admin Manage Ban</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item active">Admin Manage Ban</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-primary border-primary">
                            <i class="fas fa-users"></i>
                        </span>
                        <div class="dash-count">
                            <h3>{{ $totalUsers }}</h3>
                        </div>
                    </div>
                    <div class="dash-widget-info">
                        <h6 class="text-muted">Total Users</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-success border-success">
                            <i class="fas fa-user-check"></i>
                        </span>
                        <div class="dash-count">
                            <h3>{{ $activeUsers }}</h3>
                        </div>
                    </div>
                    <div class="dash-widget-info">
                        <h6 class="text-muted">Active Users</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-danger border-danger">
                            <i class="fas fa-user-slash"></i>
                        </span>
                        <div class="dash-count">
                            <h3>{{ $bannedUsers }}</h3>
                        </div>
                    </div>
                    <div class="dash-widget-info">
                        <h6 class="text-muted">Banned Users</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="dash-widget-header">
                        <span class="dash-widget-icon text-warning border-warning">
                            <i class="fas fa-user-clock"></i>
                        </span>
                        <div class="dash-count">
                            <h3>{{ $onlineUsers }}</h3>
                        </div>
                    </div>
                    <div class="dash-widget-info">
                        <h6 class="text-muted">Online Users</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.manage-ban.index') }}">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Search Users</label>
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Name, email, or username">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Role</label>
                                <select class="form-select" name="role">
                                    <option value="">All Roles</option>
                                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Ban Status</label>
                                <select class="form-select" name="ban_status">
                                    <option value="">All Users</option>
                                    <option value="banned" {{ request('ban_status') == 'banned' ? 'selected' : '' }}>Banned</option>
                                    <option value="not_banned" {{ request('ban_status') == 'not_banned' ? 'selected' : '' }}>Not Banned</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="btn-group w-100">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                    <a href="{{ route('admin.manage-ban.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Buttons -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.manage-ban.index') }}" class="btn btn-outline-primary {{ !request()->filled(['role', 'status', 'ban_status']) ? 'active' : '' }}">
                    <i class="fas fa-users"></i> All Users ({{ $totalUsers }})
                </a>
                <a href="{{ route('admin.manage-ban.index') }}?ban_status=banned" class="btn btn-outline-danger {{ request('ban_status') == 'banned' ? 'active' : '' }}">
                    <i class="fas fa-user-slash"></i> Banned Users ({{ $bannedUsers }})
                </a>
                <a href="{{ route('admin.manage-ban.index') }}?ban_status=not_banned&status=active" class="btn btn-outline-success {{ request('ban_status') == 'not_banned' && request('status') == 'active' ? 'active' : '' }}">
                    <i class="fas fa-user-check"></i> Active Users ({{ $activeUsers }})
                </a>
            </div>
        </div>
    </div>

    <!-- Users Management Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Users List</h4>
                    <div class="card-options">
                        <span class="badge bg-primary">{{ $users->total() }} users found</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th>User Info</th>
                                        <th>Role & Status</th>
                                        <th>Ban Status</th>
                                        <th>Last Activity</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr class="{{ $user->isBanned() ? 'table-danger' : '' }}">
                                        <td>
                                            @if($user->role !== 'admin' && $user->id !== auth()->id())
                                                <input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}">
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="position-relative">
                                                    @if($user->photo)
                                                        <img src="{{ asset($user->photo) }}" alt="User" class="rounded-circle me-3" width="40" height="40">
                                                    @else
                                                        <div class="bg-secondary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <span class="text-white">{{ substr($user->name, 0, 1) }}</span>
                                                        </div>
                                                    @endif
                                                    @if($user->is_logged_in)
                                                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                            <span class="visually-hidden">Online</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                    @if($user->username)
                                                        <br><small class="text-info">@{{ $user->username }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'secondary' }} mb-1">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                            <br>
                                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'warning' }}">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($user->isBanned())
                                                <div class="text-danger">
                                                    <i class="fas fa-ban"></i>
                                                    <strong>BANNED</strong>
                                                    @if($user->banned_at)
                                                        <br><small>{{ $user->banned_at->format('M d, Y') }}</small>
                                                    @endif
                                                    @if($user->bannedBy)
                                                        <br><small class="text-muted">by {{ $user->bannedBy->name }}</small>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check"></i> Active
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->last_login_at)
                                                <div>
                                                    <small class="d-block">{{ $user->last_login_at->format('M d, Y H:i') }}</small>
                                                    <small class="text-muted">{{ $user->last_login_at->diffForHumans() }}</small>
                                                </div>
                                                @if($user->last_login_ip)
                                                    <small class="text-info d-block">IP: {{ $user->last_login_ip }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">Never logged in</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                @if($user->role !== 'admin' && $user->id !== auth()->id())
                                                    @if($user->isBanned())
                                                        <button class="btn btn-success btn-sm" onclick="liftBanUser({{ $user->id }}, '{{ $user->name }}')" title="Lift Ban">
                                                            <i class="fas fa-user-check"></i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-danger btn-sm" onclick="banUser({{ $user->id }}, '{{ $user->name }}')" title="Ban User">
                                                            <i class="fas fa-user-slash"></i>
                                                        </button>
                                                    @endif
                                                @endif
                                                <button class="btn btn-info btn-sm" onclick="viewUserDetails({{ $user->id }})" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <div class="dropdown">
                                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#" onclick="viewUserDetails({{ $user->id }})">
                                                            <i class="fas fa-eye"></i> View Profile
                                                        </a></li>
                                                        @if($user->isBanned())
                                                            <li><a class="dropdown-item" href="#" onclick="viewBanDetails({{ $user->id }})">
                                                                <i class="fas fa-info-circle"></i> Ban Details
                                                            </a></li>
                                                        @endif
                                                        <li><a class="dropdown-item" href="#" onclick="viewLoginHistory({{ $user->id }})">
                                                            <i class="fas fa-history"></i> Login History
                                                        </a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Bulk Actions -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <select id="bulkAction" class="form-select me-2" style="width: auto;">
                                        <option value="">Bulk Actions</option>
                                        <option value="ban">Ban Selected Users</option>
                                        <option value="unban">Unban Selected Users</option>
                                    </select>
                                    <button type="button" id="applyBulkAction" class="btn btn-warning">Apply</button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Pagination -->
                                @if($users->hasPages())
                                    {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5>No users found</h5>
                            <p class="text-muted">Try adjusting your search criteria</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ban User Modal -->
<div class="modal fade" id="banUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="banUserForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ban User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        You are about to ban <strong id="banUserName"></strong>. This action will immediately log them out and prevent them from accessing the system.
                    </div>
                    <div class="mb-3">
                        <label for="ban_reason" class="form-label">Ban Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="ban_reason" name="ban_reason" rows="3" required placeholder="Enter the reason for banning this user..."></textarea>
                        <div class="form-text">This reason will be visible to other administrators.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-user-slash"></i> Ban User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Lift Ban Modal -->
<div class="modal fade" id="liftBanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="liftBanForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Lift User Ban</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        You are about to lift the ban for <strong id="liftBanUserName"></strong>. They will be able to access the system again.
                    </div>
                    <div class="mb-3">
                        <label for="ban_lift_reason" class="form-label">Reason for Lifting Ban <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="ban_lift_reason" name="ban_lift_reason" rows="3" required placeholder="Enter the reason for lifting this ban..."></textarea>
                        <div class="form-text">This reason will be logged for audit purposes.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-user-check"></i> Lift Ban
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- User Details Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userDetailsContent">
                <!-- Content will be loaded here via AJAX -->
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
// Ban user function
function banUser(userId, userName) {
    document.getElementById('banUserName').textContent = userName;
    document.getElementById('banUserForm').action = "{{ route('admin.manage-ban.ban', '') }}/" + userId;
    document.getElementById('ban_reason').value = '';
    new bootstrap.Modal(document.getElementById('banUserModal')).show();
}

// Lift ban function
function liftBanUser(userId, userName) {
    document.getElementById('liftBanUserName').textContent = userName;
    document.getElementById('liftBanForm').action = "{{ route('admin.manage-ban.lift', '') }}/" + userId;
    document.getElementById('ban_lift_reason').value = '';
    new bootstrap.Modal(document.getElementById('liftBanModal')).show();
}

// View user details - loads your existing user-details.blade.php
function viewUserDetails(userId) {
    const modal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
    modal.show();
    
    // Reset content to loading state
    document.getElementById('userDetailsContent').innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    
    fetch(`{{ route('admin.manage-ban.details', '') }}/${userId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('userDetailsContent').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('userDetailsContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Error loading user details. Please try again.
                </div>
            `;
        });
}

// View ban details - uses same modal
function viewBanDetails(userId) {
    viewUserDetails(userId);
}

// View login history - uses same modal  
function viewLoginHistory(userId) {
    viewUserDetails(userId);
}

// Select all checkbox functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Bulk actions
document.getElementById('applyBulkAction').addEventListener('click', function() {
    const action = document.getElementById('bulkAction').value;
    const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
    
    if (!action) {
        alert('Please select an action');
        return;
    }
    
    if (selectedUsers.length === 0) {
        alert('Please select at least one user');
        return;
    }
    
    if (action === 'ban') {
        // Handle bulk ban
        const reason = prompt('Enter ban reason for selected users:');
        if (reason) {
            // Submit bulk ban request
            fetch('{{ route("admin.manage-ban.bulk-ban") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    users: selectedUsers,
                    reason: reason
                })
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }
    } else if (action === 'unban') {
        // Handle bulk unban
        const reason = prompt('Enter reason for lifting bans:');
        if (reason) {
            // Submit bulk unban request
            fetch('{{ route("admin.manage-ban.bulk-unban") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    users: selectedUsers,
                    reason: reason
                })
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }
    }
});
</script>
@endpush

@endsection