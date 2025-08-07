@extends('admin.admin_master')
@section('title', 'User Ban Management')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">User Ban Management</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item active">User Ban Management</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.user-bans.index') }}" class="btn btn-primary {{ request()->routeIs('admin.user-bans.index') ? 'active' : '' }}">
                    <i class="fa fa-users"></i> All Users
                </a>
                <a href="{{ route('admin.user-bans.banned') }}" class="btn btn-danger {{ request()->routeIs('admin.user-bans.banned') ? 'active' : '' }}">
                    <i class="fa fa-user-slash"></i> Banned Users
                </a>
                <a href="{{ route('admin.user-bans.history') }}" class="btn btn-info {{ request()->routeIs('admin.user-bans.history') ? 'active' : '' }}">
                    <i class="fa fa-history"></i> Ban History
                </a>
            </div>
        </div>
    </div>

    <!-- Users Table -->
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
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Ban Status</th>
                                    <th>Last Login</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr class="{{ $user->isBanned() ? 'table-danger' : '' }}">
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($user->photo)
                                                <img src="{{ asset($user->photo) }}" alt="User" class="rounded-circle me-2" width="32" height="32">
                                            @else
                                                <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <span class="text-white small">{{ substr($user->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            {{ $user->name }}
                                            @if($user->is_logged_in)
                                                <span class="badge bg-success ms-1" title="Online">●</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'secondary' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                        @if($user->role === 'admin')
                                            <i class="fas fa-shield-alt text-primary ms-1" title="Administrator"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'warning' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->isBanned())
                                            <div class="text-danger">
                                                <span class="badge bg-danger">
                                                    <i class="fa fa-ban"></i> Banned
                                                </span>
                                                @if($user->banned_at)
                                                    <br><small class="text-primary">{{ $user->banned_at->format('M d, Y H:i') }}</small>
                                                @endif
                                                @if($user->bannedBy)
                                                    <br><small class="text-primary">by {{ $user->bannedBy->name }}</small>
                                                @endif
                                            </div>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="fa fa-check"></i> Active
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->last_login_at)
                                            {{ $user->last_login_at->format('M d, Y H:i') }}
                                            <br><small class="text-primary">{{ $user->last_login_at->diffForHumans() }}</small>
                                        @else
                                            <span class="text-primary">Never</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            {{-- Only prevent self-actions --}}
                                            @if($user->id !== auth()->id())
                                                @if($user->isBanned())
                                                    <!-- Pure Laravel Unban Button -->
                                                    <button class="btn btn-success btn-sm" 
                                                            onclick="setLiftBanUser({{ $user->id }}, '{{ $user->name }}')" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#liftBanModal" 
                                                            title="Lift Ban">
                                                        <i class="fa fa-user-check"></i> Unban
                                                    </button>
                                                @else
                                                    <!-- Pure Laravel Ban Button (including admins) -->
                                                    <button class="btn btn-danger btn-sm {{ $user->role === 'admin' ? 'btn-danger' : '' }}" 
                                                            onclick="setBanUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->role }}')" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#banUserModal" 
                                                            title="{{ $user->role === 'admin' ? 'Ban Admin User' : 'Ban User' }}">
                                                        <i class="fa fa-user-slash"></i> {{ $user->role === 'admin' ? 'Ban Admin' : 'Ban' }}
                                                    </button>
                                                @endif
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fa fa-user"></i> You
                                                </span>
                                            @endif
                                            
                                            <!-- View Details Button -->
                                            <a href="{{ route('admin.user-bans.details', $user->id) }}" 
                                               class="btn btn-info btn-sm" 
                                               title="View Details">
                                                <i class="fa fa-eye"></i> Details
                                            </a>
                                            
                                            <!-- More Actions Dropdown -->
                                            @if($user->id !== auth()->id())
                                                <div class="dropdown">
                                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                                            type="button" 
                                                            data-bs-toggle="dropdown"
                                                            title="More Actions">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if($user->isBanned())
                                                            <li>
                                                                <button class="dropdown-item text-success" 
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#liftBanModal" 
                                                                        onclick="setLiftBanUser({{ $user->id }}, '{{ $user->name }}')">
                                                                    <i class="fa fa-user-check"></i> Lift Ban
                                                                </button>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <button class="dropdown-item text-danger" 
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#banUserModal" 
                                                                        onclick="setBanUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->role }}')">
                                                                    <i class="fa fa-user-slash"></i> {{ $user->role === 'admin' ? 'Ban Admin' : 'Ban User' }}
                                                                </button>
                                                            </li>
                                                        @endif
                                                        <li><hr class="dropdown-divider"></li>
                                                        
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.user-bans.details', $user->id) }}">
                                                                <i class="fa fa-eye"></i> View Profile
                                                            </a>
                                                        </li>
                                                        
                                                        @if($user->isBanned())
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('admin.user-bans.details', $user->id) }}">
                                                                    <i class="fa fa-info-circle"></i> Ban Information
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fa fa-users fa-3x mb-3"></i>
                                            <p>No users found</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pure Laravel Ban User Modal -->
<div class="modal fade" id="banUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Pure Laravel Form Submission -->
            <form method="POST" id="banUserForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ban User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning" id="banWarningAlert">
                        <i class="fa fa-exclamation-triangle"></i>
                        You are about to ban <strong id="banUserName"></strong>. This action will immediately log them out and prevent them from accessing the system.
                    </div>
                    <div class="alert alert-danger" id="adminBanWarning" style="display: none;">
                        <i class="fa fa-shield-alt"></i>
                        <strong>Warning:</strong> You are about to ban an administrator. This is a serious action that will remove their admin access.
                    </div>
                    <div class="mb-3">
                        <label for="ban_reason" class="form-label">Ban Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="ban_reason" name="ban_reason" rows="3" required placeholder="Enter the reason for banning this user..."></textarea>
                        <div class="form-text">This reason will be visible to other administrators.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <!-- Pure Laravel Submit Button -->
                    <button type="submit" class="btn btn-danger" id="banSubmitBtn">
                        <i class="fa fa-user-slash"></i> Ban User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Pure Laravel Lift Ban Modal -->
<div class="modal fade" id="liftBanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Pure Laravel Form Submission -->
            <form method="POST" id="liftBanForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Lift User Ban</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i>
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
                    <!-- Pure Laravel Submit Button -->
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-user-check"></i> Lift Ban
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
// Pure Laravel - Only sets form action URLs, no AJAX
function setBanUser(userId, userName, userRole) {
    document.getElementById('banUserName').textContent = userName;
    document.getElementById('banUserForm').action = "{{ route('admin.user-bans.ban', '') }}/" + userId;
    document.getElementById('ban_reason').value = '';
    
    // Show admin warning if banning an admin
    const adminWarning = document.getElementById('adminBanWarning');
    const submitBtn = document.getElementById('banSubmitBtn');
    
    if (userRole === 'admin') {
        adminWarning.style.display = 'block';
        submitBtn.innerHTML = '<i class="fa fa-user-slash"></i> Ban Admin User';
        submitBtn.classList.add('btn-outline-danger');
        submitBtn.classList.remove('btn-danger');
    } else {
        adminWarning.style.display = 'none';
        submitBtn.innerHTML = '<i class="fa fa-user-slash"></i> Ban User';
        submitBtn.classList.add('btn-danger');
        submitBtn.classList.remove('btn-outline-danger');
    }
}

function setLiftBanUser(userId, userName) {
    document.getElementById('liftBanUserName').textContent = userName;
    document.getElementById('liftBanForm').action = "{{ route('admin.user-bans.lift', '') }}/" + userId;
    document.getElementById('ban_lift_reason').value = '';
}

// Auto-hide Laravel session alerts after 5 seconds
@if(session('message'))
setTimeout(function() {
    let alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        alert.style.opacity = '0';
        setTimeout(function() {
            alert.remove();
        }, 300);
    });
}, 5000);
@endif
</script>
@endpush

@endsection