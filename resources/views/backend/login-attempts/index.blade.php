@extends('admin.admin_master')
@section('title', 'Login Attempts')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Login Attempts</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item">Security</li>
                    <li class="breadcrumb-item active">Login Attempts</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="text-primary mb-1">{{ number_format($stats['total_attempts']) }}</h4>
                            <p class="text-muted mb-0">Total Attempts</p>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded p-3">
                            <i class="mdi mdi-account-multiple text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="text-success mb-1">{{ number_format($stats['successful_attempts']) }}</h4>
                            <p class="text-muted mb-0">Successful Logins</p>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded p-3">
                            <i class="mdi mdi-check-circle text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="text-danger mb-1">{{ number_format($stats['failed_attempts']) }}</h4>
                            <p class="text-muted mb-0">Failed Attempts</p>
                        </div>
                        <div class="bg-danger bg-opacity-10 rounded p-3">
                            <i class="mdi mdi-close-circle text-danger fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="text-warning mb-1">{{ number_format($stats['recent_failed_attempts']) }}</h4>
                            <p class="text-muted mb-0">Failed (24h)</p>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded p-3">
                            <i class="mdi mdi-alert-circle text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="mdi mdi-filter me-2"></i>Filters & Actions
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.login-attempts.suspicious') }}" class="btn btn-warning btn-sm">
                                <i class="mdi mdi-shield-alert me-1"></i>Suspicious Activity
                            </a>
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#cleanupModal">
                                <i class="mdi mdi-delete-sweep me-1"></i>Cleanup Old Records
                            </button>
                            <a href="{{ route('admin.login-attempts.export', request()->query()) }}" class="btn btn-success btn-sm">
                                <i class="mdi mdi-download me-1"></i>Export CSV
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.login-attempts.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All Status</option>
                                <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input type="text" name="email" class="form-control form-control-sm" 
                                   value="{{ request('email') }}" placeholder="Search by email">
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">IP Address</label>
                            <input type="text" name="ip_address" class="form-control form-control-sm" 
                                   value="{{ request('ip_address') }}" placeholder="Search by IP">
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Browser</label>
                            <select name="browser" class="form-select form-select-sm">
                                <option value="">All Browsers</option>
                                @foreach($browsers as $browser)
                                    <option value="{{ $browser }}" {{ request('browser') === $browser ? 'selected' : '' }}>
                                        {{ $browser }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Device</label>
                            <select name="device" class="form-select form-select-sm">
                                <option value="">All Devices</option>
                                @foreach($devices as $device)
                                    <option value="{{ $device }}" {{ request('device') === $device ? 'selected' : '' }}>
                                        {{ $device }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Date From</label>
                            <input type="date" name="date_from" class="form-control form-control-sm" 
                                   value="{{ request('date_from') }}">
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Date To</label>
                            <input type="date" name="date_to" class="form-control form-control-sm" 
                                   value="{{ request('date_to') }}">
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="mdi mdi-magnify me-1"></i>Filter
                                </button>
                                <a href="{{ route('admin.login-attempts.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="mdi mdi-refresh me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Attempts Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Login Attempts ({{ $loginAttempts->total() }} total)</h5>
                </div>
                <div class="card-body">
                    @if($loginAttempts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>User Info</th>
                                        <th>Status</th>
                                        <th>Location & Device</th>
                                        <th>Browser & OS</th>
                                        <th>Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loginAttempts as $attempt)
                                        <tr>
                                            <td>
                                                <strong>#{{ $attempt->id }}</strong>
                                            </td>
                                            <td>
                                                <div>
                                                    @if($attempt->email)
                                                        <strong>{{ $attempt->email }}</strong>
                                                    @else
                                                        <em class="text-muted">No email provided</em>
                                                    @endif
                                                    <br>
                                                    <small class="text-muted">IP: {{ $attempt->ip_address }}</small>
                                                    @if($attempt->user)
                                                        <br><small class="text-info">User ID: {{ $attempt->user_id }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge {{ $attempt->status_badge_class }} px-3 py-2">
                                                    <i class="mdi mdi-{{ $attempt->status === 'success' ? 'check-circle' : 'close-circle' }} me-1"></i>
                                                    {{ ucfirst($attempt->status) }}
                                                </span>
                                                @if($attempt->failure_reason)
                                                    <br><small class="text-danger">{{ $attempt->failure_reason }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <i class="mdi mdi-map-marker text-muted me-1"></i>
                                                    {{ $attempt->location }}
                                                    <br>
                                                    <i class="mdi mdi-{{ $attempt->device === 'Mobile' ? 'cellphone' : ($attempt->device === 'Tablet' ? 'tablet' : 'monitor') }} text-muted me-1"></i>
                                                    {{ $attempt->device }}
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <i class="mdi mdi-web text-muted me-1"></i>
                                                    {{ $attempt->browser }}
                                                    <br>
                                                    <i class="mdi mdi-desktop-mac text-muted me-1"></i>
                                                    {{ $attempt->operating_system }}
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $attempt->formatted_attempt_time }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $attempt->time_ago }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.login-attempts.show', $attempt->id) }}" 
                                                       class="btn btn-outline-primary btn-sm" title="View Details">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>
                                                    @if($attempt->status === 'failed')
                                                        <button type="button" 
                                                                class="btn btn-outline-warning btn-sm" 
                                                                onclick="blockIp('{{ $attempt->ip_address }}')"
                                                                title="Block IP">
                                                            <i class="mdi mdi-shield-off"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Showing {{ $loginAttempts->firstItem() }} to {{ $loginAttempts->lastItem() }} 
                                of {{ $loginAttempts->total() }} results
                            </div>
                            {{ $loginAttempts->links('pagination::bootstrap-4') }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-shield-account display-4 text-muted"></i>
                            <h5 class="mt-3 text-muted">No Login Attempts Found</h5>
                            <p class="text-muted">No login attempts match your current filters.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cleanup Modal -->
<div class="modal fade" id="cleanupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="mdi mdi-delete-sweep me-2"></i>Cleanup Old Records
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.login-attempts.clear-old') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="days" class="form-label fw-bold">Delete records older than:</label>
                        <select name="days" id="days" class="form-select" required>
                            <option value="30">30 days</option>
                            <option value="60">60 days</option>
                            <option value="90" selected>90 days</option>
                            <option value="180">180 days</option>
                            <option value="365">1 year</option>
                        </select>
                    </div>
                    <div class="alert alert-warning">
                        <i class="mdi mdi-alert me-2"></i>
                        <strong>Warning:</strong> This action cannot be undone. Old login attempt records will be permanently deleted.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="mdi mdi-delete me-1"></i>Delete Old Records
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Block IP Modal -->
<div class="modal fade" id="blockIpModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="mdi mdi-shield-off me-2"></i>Block IP Address
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.login-attempts.block-ip') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ip_address" class="form-label fw-bold">IP Address:</label>
                        <input type="text" name="ip_address" id="ip_address" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label fw-bold">Reason (Optional):</label>
                        <textarea name="reason" id="reason" class="form-control" rows="3" 
                                  placeholder="Reason for blocking this IP address"></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="mdi mdi-alert me-2"></i>
                        <strong>Warning:</strong> This will block all access from this IP address.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="mdi mdi-shield-off me-1"></i>Block IP
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function blockIp(ipAddress) {
    document.getElementById('ip_address').value = ipAddress;
    const modal = new bootstrap.Modal(document.getElementById('blockIpModal'));
    modal.show();
}

// Auto-refresh page every 30 seconds
setInterval(function() {
    if (!document.hidden) {
        location.reload();
    }
}, 30000);

// Auto-refresh notifications
@if(session('message'))
    document.addEventListener('DOMContentLoaded', function() {
        const alertType = '{{ session('alert-type', 'info') }}';
        const message = '{{ session('message') }}';
        
        const toast = document.createElement('div');
        toast.className = 'position-fixed top-0 end-0 p-3';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <div class="toast show" role="alert">
                <div class="toast-header">
                    <i class="mdi mdi-${alertType === 'success' ? 'check-circle text-success' : 'information text-info'} me-2"></i>
                    <strong class="me-auto">${alertType === 'success' ? 'Success' : 'Info'}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        }, 5000);
    });
@endif
</script>

@endsection