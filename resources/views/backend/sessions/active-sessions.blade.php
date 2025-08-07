@extends('admin.admin_master')
@section('title', 'Active Sessions')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Active Sessions</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item active">Active Sessions</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="mdi mdi-account-group display-4 text-primary"></i>
                    <h3 class="mt-2">{{ $processedSessions->count() }}</h3>
                    <p class="mb-0">Active Sessions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="mdi mdi-account-check display-4 text-success"></i>
                    <h3 class="mt-2">{{ $processedSessions->where('user_id', '!=', null)->count() }}</h3>
                    <p class="mb-0">Logged In Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="mdi mdi-account-question display-4 text-warning"></i>
                    <h3 class="mt-2">{{ $processedSessions->where('user_id', null)->count() }}</h3>
                    <p class="mb-0">Guest Sessions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="mdi mdi-devices display-4 text-info"></i>
                    <h3 class="mt-2">{{ $processedSessions->unique('ip_address')->count() }}</h3>
                    <p class="mb-0">Unique IPs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Session Management</h5>
                        <div>
                            <button class="btn btn-warning btn-sm me-2" onclick="refreshSessions()">
                                <i class="mdi mdi-refresh me-1"></i>Refresh
                            </button>
                            <form action="{{ route('admin.sessions.destroy-guest') }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                        onclick="return confirm('Are you sure you want to terminate all guest sessions?')">
                                    <i class="mdi mdi-account-off me-1"></i>Clear Guest Sessions
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Sessions Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Active Sessions (Last 30 minutes)</h5>
                </div>
                <div class="card-body">
                    @if($processedSessions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>IP Address</th>
                                        <th>Location</th>
                                        <th>Device Info</th>
                                        <th>Last Activity</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($processedSessions as $session)
                                        <tr class="{{ $session['is_current'] ? 'table-success' : '' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($session['user_id'])
                                                        <div class="avatar-sm me-3">
                                                            <span class="avatar-title bg-primary rounded-circle">
                                                                {{ substr($session['user_name'], 0, 1) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $session['user_name'] }}</h6>
                                                            <small class="text-muted">{{ $session['user_email'] }}</small>
                                                            <br>
                                                            <span class="badge bg-{{ $session['user_role'] === 'admin' ? 'danger' : 'info' }}">
                                                                {{ ucfirst($session['user_role']) }}
                                                            </span>
                                                        </div>
                                                    @else
                                                        <div class="avatar-sm me-3">
                                                            <span class="avatar-title bg-secondary rounded-circle">
                                                                <i class="mdi mdi-account-question"></i>
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">Guest User</h6>
                                                            <small class="text-muted">Not logged in</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <code>{{ $session['ip_address'] }}</code>
                                                @if($session['is_current'])
                                                    <br><small class="text-success fw-bold">Your Session</small>
                                                @endif
                                            </td>
                                            <td>
                                                <i class="mdi mdi-map-marker text-muted me-1"></i>
                                                {{ $session['location'] }}
                                            </td>
                                            <td>
                                                <div>
                                                    <i class="mdi mdi-{{ $session['device'] === 'Mobile' ? 'cellphone' : ($session['device'] === 'Tablet' ? 'tablet' : 'monitor') }} me-1"></i>
                                                    {{ $session['browser'] }} / {{ $session['device'] }}
                                                </div>
                                                <small class="text-muted" title="{{ $session['user_agent'] }}">
                                                    {{ Str::limit($session['user_agent'], 50) }}
                                                </small>
                                            </td>
                                            <td>
                                                <div>
                                                    {{ $session['last_activity']->format('M d, H:i:s') }}
                                                </div>
                                                <small class="text-muted">
                                                    {{ $session['last_activity']->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($session['last_activity']->diffInMinutes() < 5)
                                                    <span class="badge bg-success">Very Active</span>
                                                @elseif($session['last_activity']->diffInMinutes() < 15)
                                                    <span class="badge bg-warning">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Idle</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" 
                                                            class="btn btn-outline-info btn-sm" 
                                                            onclick="viewSessionDetails('{{ $session['id'] }}')"
                                                            title="View Details">
                                                        <i class="mdi mdi-eye"></i>
                                                    </button>
                                                    
                                                    @if(!$session['is_current'])
                                                        @if($session['user_id'])
                                                            <form action="{{ route('admin.sessions.destroy-user', $session['user_id']) }}" 
                                                                  method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="btn btn-outline-warning btn-sm" 
                                                                        onclick="return confirm('Terminate all sessions for this user?')"
                                                                        title="Terminate User Sessions">
                                                                    <i class="mdi mdi-account-off"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        
                                                        <form action="{{ route('admin.sessions.destroy', $session['id']) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-outline-danger btn-sm" 
                                                                    onclick="return confirm('Terminate this session?')"
                                                                    title="Terminate Session">
                                                                <i class="mdi mdi-close"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button type="button" 
                                                                class="btn btn-outline-secondary btn-sm" 
                                                                disabled
                                                                title="Cannot terminate your own session">
                                                            <i class="mdi mdi-lock"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-account-off display-4 text-muted"></i>
                            <h5 class="mt-3 text-muted">No Active Sessions</h5>
                            <p class="text-muted">There are no active sessions in the last 30 minutes.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Session Details Modal -->
<div class="modal fade" id="sessionDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Session Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="sessionDetailsContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function refreshSessions() {
    window.location.reload();
}

function viewSessionDetails(sessionId) {
    const modal = new bootstrap.Modal(document.getElementById('sessionDetailsModal'));
    const content = document.getElementById('sessionDetailsContent');
    
    // Show loading
    content.innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    
    modal.show();
    
    // Fetch session details
    fetch(`{{ route('admin.sessions.details', '') }}/${sessionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.session) {
                const session = data.session;
                content.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>User Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>${session.user_name}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>${session.user_email}</td>
                                </tr>
                                <tr>
                                    <td><strong>Role:</strong></td>
                                    <td><span class="badge bg-info">${session.user_role}</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Session Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Session ID:</strong></td>
                                    <td><code>${session.id}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>IP Address:</strong></td>
                                    <td><code>${session.ip_address}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>Last Activity:</strong></td>
                                    <td>${session.last_activity}</td>
                                </tr>
                                <tr>
                                    <td><strong>Duration:</strong></td>
                                    <td>${session.session_duration}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Browser & Device Details</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Browser:</strong></td>
                                    <td>${session.browser_details.browser} ${session.browser_details.version}</td>
                                </tr>
                                <tr>
                                    <td><strong>Platform:</strong></td>
                                    <td>${session.browser_details.platform}</td>
                                </tr>
                                <tr>
                                    <td><strong>User Agent:</strong></td>
                                    <td><small>${session.browser_details.full_string}</small></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                `;
            } else {
                content.innerHTML = '<div class="alert alert-danger">Failed to load session details.</div>';
            }
        })
        .catch(error => {
            content.innerHTML = '<div class="alert alert-danger">Error loading session details.</div>';
        });
}

// Auto-refresh every 30 seconds
setInterval(function() {
    // Only refresh if no modal is open
    if (!document.querySelector('.modal.show')) {
        window.location.reload();
    }
}, 30000);
</script>

@endsection