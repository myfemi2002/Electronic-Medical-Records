@extends('admin.admin_master')
@section('title', 'Login Attempt Details')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Login Attempt Details #{{ $loginAttempt->id }}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item">Security</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.login-attempts.index') }}">Login Attempts</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('admin.login-attempts.index') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-2"></i>Back to Login Attempts
            </a>
        </div>
    </div>

    <!-- Main Details -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-information me-2"></i>Attempt Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted">Status</h6>
                                <span class="badge {{ $loginAttempt->status_badge_class }} px-3 py-2 fs-6">
                                    <i class="mdi mdi-{{ $loginAttempt->status === 'success' ? 'check-circle' : 'close-circle' }} me-1"></i>
                                    {{ ucfirst($loginAttempt->status) }}
                                </span>
                                @if($loginAttempt->failure_reason)
                                    <div class="mt-2">
                                        <small class="text-danger">
                                            <strong>Failure Reason:</strong> {{ $loginAttempt->failure_reason }}
                                        </small>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-4">
                                <h6 class="text-muted">Email Address</h6>
                                <p class="mb-0">
                                    @if($loginAttempt->email)
                                        <strong>{{ $loginAttempt->email }}</strong>
                                        @if($loginAttempt->user)
                                            <br><small class="text-info">Associated User ID: {{ $loginAttempt->user_id }}</small>
                                        @endif
                                    @else
                                        <em class="text-muted">No email provided</em>
                                    @endif
                                </p>
                            </div>

                            <div class="mb-4">
                                <h6 class="text-muted">Time Information</h6>
                                <p class="mb-0">
                                    <strong>{{ $loginAttempt->formatted_attempt_time }}</strong>
                                    <br><small class="text-muted">{{ $loginAttempt->time_ago }}</small>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted">IP Address & Location</h6>
                                <p class="mb-0">
                                    <i class="mdi mdi-ip me-2"></i><strong>{{ $loginAttempt->ip_address }}</strong>
                                    <br>
                                    <i class="mdi mdi-map-marker me-2"></i>{{ $loginAttempt->location }}
                                </p>
                            </div>

                            <div class="mb-4">
                                <h6 class="text-muted">Device Information</h6>
                                <p class="mb-0">
                                    <i class="mdi mdi-{{ $loginAttempt->device === 'Mobile' ? 'cellphone' : ($loginAttempt->device === 'Tablet' ? 'tablet' : 'monitor') }} me-2"></i>
                                    {{ $loginAttempt->device }}
                                    <br>
                                    <i class="mdi mdi-desktop-mac me-2"></i>{{ $loginAttempt->operating_system }}
                                    <br>
                                    <i class="mdi mdi-web me-2"></i>{{ $loginAttempt->browser }}
                                </p>
                            </div>

                            @if($loginAttempt->user_agent)
                                <div class="mb-4">
                                    <h6 class="text-muted">User Agent</h6>
                                    <p class="mb-0">
                                        <small class="font-monospace text-muted">{{ $loginAttempt->user_agent }}</small>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Information (if available) -->
            @if($loginAttempt->user)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="mdi mdi-account me-2"></i>Associated User Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="text-muted">User Details</h6>
                                    <p class="mb-0">
                                        <strong>Name:</strong> {{ $loginAttempt->user->name }}
                                        <br>
                                        <strong>Email:</strong> {{ $loginAttempt->user->email }}
                                        <br>
                                        <strong>Role:</strong> {{ ucfirst($loginAttempt->user->role ?? 'N/A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="text-muted">Account Status</h6>
                                    <p class="mb-0">
                                        <strong>Status:</strong> 
                                        <span class="badge bg-{{ $loginAttempt->user->is_logged_in ? 'success' : 'secondary' }}">
                                            {{ $loginAttempt->user->is_logged_in ? 'Online' : 'Offline' }}
                                        </span>
                                        <br>
                                        <strong>Last Login:</strong> 
                                        {{ $loginAttempt->user->last_login_at ? $loginAttempt->user->last_login_at->format('M d, Y g:i A') : 'Never' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Action Buttons -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-cog me-2"></i>Actions
                    </h5>
                </div>
                <div class="card-body">
                    @if($loginAttempt->status === 'failed')
                        <button type="button" class="btn btn-warning w-100 mb-2" onclick="blockIp('{{ $loginAttempt->ip_address }}')">
                            <i class="mdi mdi-shield-off me-2"></i>Block IP Address
                        </button>
                    @endif
                    
                    <a href="{{ route('admin.login-attempts.index', ['ip_address' => $loginAttempt->ip_address]) }}" 
                       class="btn btn-info w-100 mb-2">
                        <i class="mdi mdi-ip me-2"></i>View All from this IP
                    </a>
                    
                    @if($loginAttempt->email)
                        <a href="{{ route('admin.login-attempts.index', ['email' => $loginAttempt->email]) }}" 
                           class="btn btn-primary w-100 mb-2">
                            <i class="mdi mdi-email me-2"></i>View All from this Email
                        </a>
                    @endif
                    
                    <button type="button" class="btn btn-secondary w-100" onclick="window.print()">
                        <i class="mdi mdi-printer me-2"></i>Print Details
                    </button>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-chart-bar me-2"></i>Quick Stats
                    </h5>
                </div>
                <div class="card-body">
                    @if($loginAttempt->email)
                        <div class="mb-3">
                            <h6 class="text-muted">Email Statistics (24h)</h6>
                            @php
                                $emailFailedCount = \App\Models\LoginAttempt::getFailedAttemptsCount($loginAttempt->email);
                                $emailTotalCount = \App\Models\LoginAttempt::where('email', $loginAttempt->email)
                                    ->where('attempted_at', '>=', \Carbon\Carbon::now()->subDay())
                                    ->count();
                            @endphp
                            <p class="mb-0">
                                <strong>Total Attempts:</strong> {{ $emailTotalCount }}
                                <br>
                                <strong>Failed Attempts:</strong> 
                                <span class="text-{{ $emailFailedCount > 3 ? 'danger' : 'muted' }}">{{ $emailFailedCount }}</span>
                            </p>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <h6 class="text-muted">IP Statistics (24h)</h6>
                        @php
                            $ipFailedCount = \App\Models\LoginAttempt::getFailedAttemptsCountByIp($loginAttempt->ip_address);
                            $ipTotalCount = \App\Models\LoginAttempt::where('ip_address', $loginAttempt->ip_address)
                                ->where('attempted_at', '>=', \Carbon\Carbon::now()->subDay())
                                ->count();
                        @endphp
                        <p class="mb-0">
                            <strong>Total Attempts:</strong> {{ $ipTotalCount }}
                            <br>
                            <strong>Failed Attempts:</strong> 
                            <span class="text-{{ $ipFailedCount > 5 ? 'danger' : 'muted' }}">{{ $ipFailedCount }}</span>
                        </p>
                    </div>
                    
                    <div class="mb-0">
                        <h6 class="text-muted">Risk Assessment</h6>
                        @php
                            $riskLevel = 'Low';
                            $riskClass = 'success';
                            
                            if ($loginAttempt->status === 'failed') {
                                if ($emailFailedCount > 5 || $ipFailedCount > 10) {
                                    $riskLevel = 'High';
                                    $riskClass = 'danger';
                                } elseif ($emailFailedCount > 3 || $ipFailedCount > 5) {
                                    $riskLevel = 'Medium';
                                    $riskClass = 'warning';
                                }
                            }
                        @endphp
                        <span class="badge bg-{{ $riskClass }} px-3 py-2">
                            <i class="mdi mdi-shield-{{ $riskLevel === 'High' ? 'alert' : ($riskLevel === 'Medium' ? 'half-full' : 'check') }} me-1"></i>
                            {{ $riskLevel }} Risk
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Attempts -->
    <div class="row mt-4">
        @if($relatedByIp->count() > 0)
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="mdi mdi-ip me-2"></i>Recent Attempts from Same IP
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($relatedByIp as $attempt)
                                        <tr>
                                            <td>
                                                @if($attempt->email)
                                                    {{ Str::limit($attempt->email, 20) }}
                                                @else
                                                    <em class="text-muted">No email</em>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $attempt->status_badge_class }}">
                                                    {{ ucfirst($attempt->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>{{ $attempt->attempted_at->format('M d, g:i A') }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.login-attempts.index', ['ip_address' => $loginAttempt->ip_address]) }}" 
                               class="btn btn-outline-primary btn-sm">
                                View All from this IP
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($relatedByEmail->count() > 0 && $loginAttempt->email)
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="mdi mdi-email me-2"></i>Recent Attempts from Same Email
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>IP Address</th>
                                        <th>Status</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($relatedByEmail as $attempt)
                                        <tr>
                                            <td>{{ $attempt->ip_address }}</td>
                                            <td>
                                                <span class="badge {{ $attempt->status_badge_class }}">
                                                    {{ ucfirst($attempt->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>{{ $attempt->attempted_at->format('M d, g:i A') }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.login-attempts.index', ['email' => $loginAttempt->email]) }}" 
                               class="btn btn-outline-primary btn-sm">
                                View All from this Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
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

// Print styles
const printStyles = `
    <style media="print">
        .btn, .modal, .breadcrumb { display: none !important; }
        .card { border: 1px solid #ddd !important; margin-bottom: 20px; }
        .page-header { border-bottom: 2px solid #000; padding-bottom: 10px; }
    </style>
`;
document.head.insertAdjacentHTML('beforeend', printStyles);
</script>

@endsection