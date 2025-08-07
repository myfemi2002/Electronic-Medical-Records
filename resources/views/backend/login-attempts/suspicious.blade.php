@extends('admin.admin_master')
@section('title', 'Suspicious Login Activities')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Suspicious Login Activities</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item">Security</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.login-attempts.index') }}">Login Attempts</a></li>
                    <li class="breadcrumb-item active">Suspicious Activities</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('admin.login-attempts.index') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-2"></i>Back to All Login Attempts
            </a>
        </div>
    </div>

    <!-- Alert Summary -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-shield-alert me-2"></i>Security Alert Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="border-end">
                                <h3 class="text-danger">{{ $suspiciousIps->count() }}</h3>
                                <p class="text-muted mb-0">Suspicious IP Addresses</p>
                                <small class="text-muted">Multiple failed attempts (24h)</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-end">
                                <h3 class="text-warning">{{ $suspiciousEmails->count() }}</h3>
                                <p class="text-muted mb-0">Targeted Email Accounts</p>
                                <small class="text-muted">Multiple failed attempts (24h)</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h3 class="text-info">{{ $unusualTimes->count() }}</h3>
                            <p class="text-muted mb-0">Unusual Time Logins</p>
                            <small class="text-muted">Outside business hours (7 days)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Suspicious IP Addresses -->
    @if($suspiciousIps->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0">
                            <i class="mdi mdi-ip me-2"></i>Suspicious IP Addresses (24 hours)
                        </h5>
                        <p class="mb-0 opacity-75">IP addresses with 5+ failed login attempts</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>IP Address</th>
                                        <th>Failed Attempts</th>
                                        <th>Location</th>
                                        <th>Last Attempt</th>
                                        <th>Risk Level</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($suspiciousIps as $ipData)
                                        @php
                                            $lastAttempt = \App\Models\LoginAttempt::where('ip_address', $ipData->ip_address)
                                                ->orderBy('attempted_at', 'desc')
                                                ->first();
                                            
                                            $riskLevel = 'Medium';
                                            $riskClass = 'warning';
                                            if ($ipData->attempts >= 15) {
                                                $riskLevel = 'Critical';
                                                $riskClass = 'danger';
                                            } elseif ($ipData->attempts >= 10) {
                                                $riskLevel = 'High';
                                                $riskClass = 'danger';
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $ipData->ip_address }}</strong>
                                                @if($lastAttempt && $lastAttempt->location !== $ipData->ip_address)
                                                    <br><small class="text-muted">{{ $lastAttempt->location }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-danger fs-6">{{ $ipData->attempts }}</span>
                                            </td>
                                            <td>
                                                @if($lastAttempt)
                                                    <i class="mdi mdi-map-marker me-1"></i>{{ $lastAttempt->location }}
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="mdi mdi-{{ $lastAttempt->device === 'Mobile' ? 'cellphone' : 'monitor' }} me-1"></i>
                                                        {{ $lastAttempt->device }} - {{ $lastAttempt->browser }}
                                                    </small>
                                                @else
                                                    <span class="text-muted">Unknown</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($lastAttempt)
                                                    {{ $lastAttempt->attempted_at->format('M d, g:i A') }}
                                                    <br><small class="text-muted">{{ $lastAttempt->attempted_at->diffForHumans() }}</small>
                                                @else
                                                    <span class="text-muted">Unknown</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $riskClass }} px-3 py-2">
                                                    <i class="mdi mdi-shield-alert me-1"></i>{{ $riskLevel }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.login-attempts.index', ['ip_address' => $ipData->ip_address]) }}" 
                                                       class="btn btn-outline-info btn-sm" title="View All Attempts">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-outline-danger btn-sm" 
                                                            onclick="blockIp('{{ $ipData->ip_address }}')"
                                                            title="Block IP">
                                                        <i class="mdi mdi-shield-off"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Targeted Email Accounts -->
    @if($suspiciousEmails->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0">
                            <i class="mdi mdi-email-alert me-2"></i>Targeted Email Accounts (24 hours)
                        </h5>
                        <p class="mb-0">Email accounts with 3+ failed login attempts</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Email Address</th>
                                        <th>Failed Attempts</th>
                                        <th>Unique IPs</th>
                                        <th>Last Attempt</th>
                                        <th>Account Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($suspiciousEmails as $emailData)
                                        @php
                                            $lastAttempt = \App\Models\LoginAttempt::where('email', $emailData->email)
                                                ->orderBy('attempted_at', 'desc')
                                                ->first();
                                            
                                            $uniqueIps = \App\Models\LoginAttempt::where('email', $emailData->email)
                                                ->where('attempted_at', '>=', \Carbon\Carbon::now()->subDay())
                                                ->distinct('ip_address')
                                                ->count();
                                                
                                            $user = \App\Models\User::where('email', $emailData->email)->first();
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $emailData->email }}</strong>
                                                @if($user)
                                                    <br><small class="text-info">User ID: {{ $user->id }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark fs-6">{{ $emailData->attempts }}</span>
                                            </td>
                                            <td>
                                                @if($uniqueIps > 1)
                                                    <span class="badge bg-danger">{{ $uniqueIps }} IPs</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $uniqueIps }} IP</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($lastAttempt)
                                                    {{ $lastAttempt->attempted_at->format('M d, g:i A') }}
                                                    <br><small class="text-muted">from {{ $lastAttempt->ip_address }}</small>
                                                @else
                                                    <span class="text-muted">Unknown</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user)
                                                    <span class="badge bg-{{ $user->is_logged_in ? 'success' : 'secondary' }}">
                                                        {{ $user->is_logged_in ? 'Online' : 'Offline' }}
                                                    </span>
                                                    @if(\App\Models\LoginAttempt::shouldLockAccount($emailData->email))
                                                        <br><span class="badge bg-danger mt-1">Account Locked</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">No Account</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.login-attempts.index', ['email' => $emailData->email]) }}" 
                                                       class="btn btn-outline-info btn-sm" title="View All Attempts">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>
                                                    @if($user)
                                                        <a href="#" class="btn btn-outline-primary btn-sm" title="View User Details">
                                                            <i class="mdi mdi-account"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Unusual Time Login Attempts -->
    @if($unusualTimes->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="mdi mdi-clock-alert me-2"></i>Unusual Time Login Attempts (7 days)
                        </h5>
                        <p class="mb-0 opacity-75">Login attempts outside business hours (6 AM - 10 PM)</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>IP Address</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Device Info</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($unusualTimes->take(50) as $attempt)
                                        <tr>
                                            <td>
                                                @if($attempt->email)
                                                    {{ $attempt->email }}
                                                @else
                                                    <em class="text-muted">No email</em>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $attempt->ip_address }}
                                                @if($attempt->location !== $attempt->ip_address)
                                                    <br><small class="text-muted">{{ $attempt->location }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $attempt->attempted_at->format('M d, Y') }}</strong>
                                                <br>
                                                <span class="badge bg-{{ $attempt->attempted_at->hour < 6 || $attempt->attempted_at->hour > 22 ? 'danger' : 'warning' }}">
                                                    {{ $attempt->attempted_at->format('g:i A') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $attempt->status_badge_class }}">
                                                    {{ ucfirst($attempt->status) }}
                                                </span>
                                                @if($attempt->failure_reason)
                                                    <br><small class="text-danger">{{ $attempt->failure_reason }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <i class="mdi mdi-{{ $attempt->device === 'Mobile' ? 'cellphone' : 'monitor' }} me-1"></i>
                                                {{ $attempt->device }}
                                                <br>
                                                <small class="text-muted">{{ $attempt->browser }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.login-attempts.show', $attempt->id) }}" 
                                                   class="btn btn-outline-primary btn-sm" title="View Details">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($unusualTimes->count() > 50)
                            <div class="text-center mt-3">
                                <p class="text-muted">Showing first 50 results. Use filters in main view to see more.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- No Suspicious Activity -->
    @if($suspiciousIps->count() === 0 && $suspiciousEmails->count() === 0 && $unusualTimes->count() === 0)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="mdi mdi-shield-check display-4 text-success"></i>
                        <h4 class="mt-3 text-success">No Suspicious Activity Detected</h4>
                        <p class="text-muted">All recent login attempts appear to be normal. The system is monitoring for:</p>
                        <ul class="list-unstyled text-muted">
                            <li>• IP addresses with 5+ failed attempts in 24 hours</li>
                            <li>• Email accounts with 3+ failed attempts in 24 hours</li>
                            <li>• Login attempts outside business hours (6 AM - 10 PM)</li>
                        </ul>
                        <a href="{{ route('admin.login-attempts.index') }}" class="btn btn-primary mt-3">
                            <i class="mdi mdi-eye me-2"></i>View All Login Attempts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Block IP Modal -->
<div class="modal fade" id="blockIpModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="mdi mdi-shield-off me-2"></i>Block Suspicious IP Address
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
                        <label for="reason" class="form-label fw-bold">Reason:</label>
                        <textarea name="reason" id="reason" class="form-control" rows="3" 
                                  placeholder="Reason for blocking this IP address">Multiple failed login attempts detected - suspicious activity</textarea>
                    </div>
                    <div class="alert alert-danger">
                        <i class="mdi mdi-alert me-2"></i>
                        <strong>Warning:</strong> This will block all access from this IP address due to suspicious activity.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="mdi mdi-shield-off me-1"></i>Block IP Address
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

// Auto-refresh every 60 seconds for real-time monitoring
setInterval(function() {
    if (!document.hidden) {
        location.reload();
    }
}, 60000);
</script>

@endsection