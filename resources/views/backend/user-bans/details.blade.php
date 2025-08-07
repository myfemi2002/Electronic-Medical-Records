@extends('admin.admin_master')
@section('title', 'User Details')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">User Details</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.user-bans.index') }}">User Ban Management</a></li>
                    <li class="breadcrumb-item active">User Details</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- User Information Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">User Information</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($user->photo)
                            <img src="{{ asset($user->photo) }}" alt="User Photo" class="rounded-circle" width="80" height="80">
                        @else
                            <div class="bg-secondary rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <span class="text-white h4 mb-0">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <h5 class="mt-2 mb-0">{{ $user->name }}</h5>
                        <p class="text-muted">{{ $user->email }}</p>
                        
                        {{-- Show if current user --}}
                        @if($user->id === auth()->id())
                            <span class="badge bg-info">
                                <i class="fas fa-user"></i> This is you
                            </span>
                        @endif
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-muted">Role</label>
                            <p class="mb-1">
                                <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'secondary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                                @if($user->role === 'admin')
                                    <i class="fas fa-shield-alt text-primary ms-1" title="Administrator"></i>
                                @endif
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted">Account Status</label>
                            <p class="mb-1">
                                <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted">Ban Status</label>
                            <p class="mb-1">
                                @if($user->isBanned())
                                    <span class="badge bg-danger">
                                        <i class="fas fa-ban"></i> Banned
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-check"></i> Active
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted">Member Since</label>
                            <p class="mb-1">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                        @if($user->last_login_at)
                            <div class="col-12">
                                <label class="form-label text-muted">Last Login</label>
                                <p class="mb-1">{{ $user->last_login_at->format('M d, Y H:i') }}</p>
                                <small class="text-muted">{{ $user->last_login_at->diffForHumans() }}</small>
                            </div>
                        @endif
                    </div>

                    {{-- Action buttons - Only prevent self-actions --}}
                    @if($user->id !== auth()->id())
                        <div class="mt-3 pt-3 border-top">
                            @if($user->isBanned())
                                <button class="btn btn-success btn-sm w-100" data-bs-toggle="modal" data-bs-target="#liftBanModal">
                                    <i class="fas fa-user-check"></i> Lift Ban
                                </button>
                            @else
                                @if($user->role === 'admin')
                                    <div class="alert alert-warning alert-sm p-2 mb-2">
                                        <small><i class="fas fa-shield-alt"></i> This user is an administrator</small>
                                    </div>
                                @endif
                                <button class="btn btn-danger btn-sm w-100 {{ $user->role === 'admin' ? 'btn-outline-danger' : '' }}" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#banUserModal">
                                    <i class="fas fa-user-slash"></i> {{ $user->role === 'admin' ? 'Ban Admin' : 'Ban User' }}
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="mt-3 pt-3 border-top text-center">
                            <div class="alert alert-info alert-sm p-2">
                                <small><i class="fas fa-info-circle"></i> You cannot perform ban actions on yourself</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ban Details Card -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Ban Information</h4>
                </div>
                <div class="card-body">
                    @if($user->isBanned())
                        <!-- Current Ban Information -->
                        <div class="alert alert-danger">
                            <h5 class="alert-heading">
                                <i class="fas fa-ban"></i> User is Currently Banned
                                @if($user->role === 'admin')
                                    <span class="badge bg-warning ms-2">ADMIN USER</span>
                                @endif
                            </h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Banned On:</strong><br>
                                    {{ $user->banned_at ? $user->banned_at->format('F d, Y \a\t H:i') : 'Unknown' }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Banned By:</strong><br>
                                    {{ $user->bannedBy ? $user->bannedBy->name : 'Unknown' }}
                                    @if($user->bannedBy && $user->bannedBy->role === 'admin')
                                        <span class="badge bg-primary">Admin</span>
                                    @endif
                                </div>
                            </div>
                            @if($user->ban_reason)
                                <div class="mt-3">
                                    <strong>Ban Reason:</strong><br>
                                    <div class="bg-light p-2 rounded mt-1">
                                        {{ $user->ban_reason }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-success">
                            <h5 class="alert-heading">
                                <i class="fas fa-check-circle"></i> User is Currently Active
                                @if($user->role === 'admin')
                                    <span class="badge bg-primary ms-2">ADMIN USER</span>
                                @endif
                            </h5>
                            <p class="mb-0">This user is not currently banned and can access the system normally.</p>
                        </div>
                    @endif

                    <!-- Previous Ban History -->
                    @if($user->ban_lifted_at)
                        <div class="card mt-3">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">Previous Ban Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Ban Lifted On:</strong><br>
                                        {{ $user->ban_lifted_at->format('F d, Y \a\t H:i') }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Ban Lifted By:</strong><br>
                                        {{ $user->banLiftedBy ? $user->banLiftedBy->name : 'Unknown' }}
                                        @if($user->banLiftedBy && $user->banLiftedBy->role === 'admin')
                                            <span class="badge bg-primary">Admin</span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($user->ban_lift_reason)
                                    <div class="mt-3">
                                        <strong>Reason for Lifting Ban:</strong><br>
                                        <div class="bg-light p-2 rounded mt-1">
                                            {{ $user->ban_lift_reason }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Admin Ban Warning Info -->
                    @if($user->role === 'admin')
                        <div class="card mt-3 border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-shield-alt"></i> Administrator Account Notice
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-2"><strong>This is an administrator account.</strong></p>
                                <ul class="mb-0">
                                    <li>Banning this user will remove their administrative privileges</li>
                                    <li>They will be logged out immediately if banned</li>
                                    <li>All admin actions require careful consideration</li>
                                </ul>
                            </div>
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
            <form method="POST" action="{{ route('admin.user-bans.ban', $user->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ $user->role === 'admin' ? 'Ban Administrator' : 'Ban User' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($user->role === 'admin')
                        <div class="alert alert-danger">
                            <i class="fas fa-shield-alt"></i>
                            <strong>Critical Action Warning:</strong> You are about to ban an administrator (<strong>{{ $user->name }}</strong>). 
                            This will immediately remove their admin access and log them out of the system.
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            You are about to ban <strong>{{ $user->name }}</strong>. This action will immediately log them out and prevent them from accessing the system.
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="ban_reason" class="form-label">Ban Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="ban_reason" name="ban_reason" rows="3" required 
                                  placeholder="{{ $user->role === 'admin' ? 'Enter detailed reason for banning this administrator...' : 'Enter the reason for banning this user...' }}"></textarea>
                        @if($user->role === 'admin')
                            <div class="form-text text-danger">Administrative bans require detailed justification for audit purposes.</div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn {{ $user->role === 'admin' ? 'btn-outline-danger' : 'btn-danger' }}">
                        <i class="fas fa-user-slash"></i> {{ $user->role === 'admin' ? 'Ban Administrator' : 'Ban User' }}
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
            <form method="POST" action="{{ route('admin.user-bans.lift', $user->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Lift User Ban</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        You are about to lift the ban for <strong>{{ $user->name }}</strong>{{ $user->role === 'admin' ? ' (Administrator)' : '' }}. 
                        They will be able to access the system again.
                    </div>
                    <div class="mb-3">
                        <label for="ban_lift_reason" class="form-label">Reason for Lifting Ban <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="ban_lift_reason" name="ban_lift_reason" rows="3" required placeholder="Enter the reason for lifting this ban..."></textarea>
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

@endsection