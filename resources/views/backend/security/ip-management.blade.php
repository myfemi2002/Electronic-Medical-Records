@extends('admin.admin_master')
@section('title', 'IP Management')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">IP Address Management</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item active">IP Management</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="mdi mdi-shield-off display-4 text-danger"></i>
                    <h3 class="mt-2">{{ $stats['total_blocked'] }}</h3>
                    <p class="mb-0">Blocked IPs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="mdi mdi-shield-check display-4 text-success"></i>
                    <h3 class="mt-2">{{ $stats['total_whitelisted'] }}</h3>
                    <p class="mb-0">Whitelisted IPs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="mdi mdi-alert-circle display-4 text-warning"></i>
                    <h3 class="mt-2">{{ $stats['blocked_today'] }}</h3>
                    <p class="mb-0">Blocked Today</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="mdi mdi-eye display-4 text-info"></i>
                    <h3 class="mt-2">{{ $stats['access_attempts_today'] }}</h3>
                    <p class="mb-0">Access Attempts Today</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-shield-off me-2"></i>Block IP Address
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.ip.block') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ip_address" class="form-label">IP Address <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('ip_address') is-invalid @enderror" 
                                           id="ip_address" 
                                           name="ip_address" 
                                           value="{{ old('ip_address') }}" 
                                           placeholder="192.168.1.1"
                                           required>
                                    @error('ip_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duration" class="form-label">Duration (Hours)</label>
                                    <input type="number" 
                                           class="form-control @error('duration') is-invalid @enderror" 
                                           id="duration" 
                                           name="duration" 
                                           value="{{ old('duration') }}" 
                                           placeholder="Leave empty for permanent"
                                           min="1">
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('reason') is-invalid @enderror" 
                                   id="reason" 
                                   name="reason" 
                                   value="{{ old('reason') }}" 
                                   placeholder="Suspicious activity, spam, etc."
                                   required>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-danger">
                            <i class="mdi mdi-shield-off me-1"></i>Block IP
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-shield-check me-2"></i>Whitelist IP Address
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.ip.whitelist') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="whitelist_ip" class="form-label">IP Address <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('ip_address') is-invalid @enderror" 
                                   id="whitelist_ip" 
                                   name="ip_address" 
                                   value="{{ old('ip_address') }}" 
                                   placeholder="192.168.1.1"
                                   required>
                            @error('ip_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('description') is-invalid @enderror" 
                                   id="description" 
                                   name="description" 
                                   value="{{ old('description') }}" 
                                   placeholder="Office IP, Admin access, etc."
                                   required>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-shield-check me-1"></i>Whitelist IP
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Bulk Operations</h5>
                        <div>
                            <form action="{{ route('admin.ip.clear-expired') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning btn-sm">
                                    <i class="mdi mdi-clock-alert me-1"></i>Clear Expired Blocks
                                </button>
                            </form>
                            <a href="{{ route('admin.ip.access-logs') }}" class="btn btn-outline-info btn-sm">
                                <i class="mdi mdi-file-document me-1"></i>Access Logs
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.ip.bulk-block') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ip_list" class="form-label">IP Addresses (one per line) <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('ip_list') is-invalid @enderror" 
                                              id="ip_list" 
                                              name="ip_list" 
                                              rows="5" 
                                              placeholder="192.168.1.1&#10;10.0.0.1&#10;172.16.0.1"
                                              required>{{ old('ip_list') }}</textarea>
                                    @error('ip_list')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bulk_reason" class="form-label">Reason <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('reason') is-invalid @enderror" 
                                           id="bulk_reason" 
                                           name="reason" 
                                           value="{{ old('reason') }}" 
                                           placeholder="Bulk block reason"
                                           required>
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="bulk_duration" class="form-label">Duration (Hours)</label>
                                    <input type="number" 
                                           class="form-control @error('duration') is-invalid @enderror" 
                                           id="bulk_duration" 
                                           name="duration" 
                                           value="{{ old('duration') }}" 
                                           placeholder="Leave empty for permanent"
                                           min="1">
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="mdi mdi-shield-off me-1"></i>Bulk Block IPs
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs for Blocked and Whitelisted IPs -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="ipTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="blocked-tab" data-bs-toggle="tab" data-bs-target="#blocked" type="button" role="tab">
                                <i class="mdi mdi-shield-off me-2"></i>Blocked IPs ({{ $blockedIPs->total() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="whitelist-tab" data-bs-toggle="tab" data-bs-target="#whitelist" type="button" role="tab">
                                <i class="mdi mdi-shield-check me-2"></i>Whitelisted IPs ({{ $whitelistedIPs->total() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="recent-tab" data-bs-toggle="tab" data-bs-target="#recent" type="button" role="tab">
                                <i class="mdi mdi-clock me-2"></i>Recent Access ({{ $recentAccess->count() }})
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="ipTabsContent">
                        <!-- Blocked IPs Tab -->
                        <div class="tab-pane fade show active" id="blocked" role="tabpanel">
                            @if($blockedIPs->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>IP Address</th>
                                                <th>Reason</th>
                                                <th>Blocked By</th>
                                                <th>Status</th>
                                                <th>Blocked Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($blockedIPs as $blockedIP)
                                                <tr>
                                                    <td>
                                                        <code>{{ $blockedIP->ip_address }}</code>
                                                    </td>
                                                    <td>{{ $blockedIP->reason }}</td>
                                                    <td>
                                                        @if($blockedIP->blocker)
                                                            {{ $blockedIP->blocker->name }}
                                                        @else
                                                            System
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($blockedIP->isPermanent())
                                                            <span class="badge bg-danger">Permanent</span>
                                                        @elseif($blockedIP->isExpired())
                                                            <span class="badge bg-secondary">Expired</span>
                                                        @else
                                                            <span class="badge bg-warning">Active</span>
                                                            <br><small class="text-muted">Expires: {{ $blockedIP->expires_at->format('M d, Y H:i') }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $blockedIP->created_at->format('M d, Y H:i') }}
                                                        <br><small class="text-muted">{{ $blockedIP->created_at->diffForHumans() }}</small>
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('admin.ip.unblock', $blockedIP->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-outline-success btn-sm" 
                                                                    onclick="return confirm('Are you sure you want to unblock this IP?')"
                                                                    title="Unblock IP">
                                                                <i class="mdi mdi-shield-check"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $blockedIPs->links() }}
                            @else
                                <div class="text-center py-5">
                                    <i class="mdi mdi-shield-check display-4 text-muted"></i>
                                    <h5 class="mt-3 text-muted">No Blocked IPs</h5>
                                    <p class="text-muted">No IP addresses are currently blocked.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Whitelisted IPs Tab -->
                        <div class="tab-pane fade" id="whitelist" role="tabpanel">
                            @if($whitelistedIPs->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>IP Address</th>
                                                <th>Description</th>
                                                <th>Added By</th>
                                                <th>Added Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($whitelistedIPs as $whitelistedIP)
                                                <tr>
                                                    <td>
                                                        <code>{{ $whitelistedIP->ip_address }}</code>
                                                    </td>
                                                    <td>{{ $whitelistedIP->description }}</td>
                                                    <td>
                                                        @if($whitelistedIP->adder)
                                                            {{ $whitelistedIP->adder->name }}
                                                        @else
                                                            System
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $whitelistedIP->created_at->format('M d, Y H:i') }}
                                                        <br><small class="text-muted">{{ $whitelistedIP->created_at->diffForHumans() }}</small>
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('admin.ip.remove-whitelist', $whitelistedIP->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-outline-danger btn-sm" 
                                                                    onclick="return confirm('Are you sure you want to remove this IP from whitelist?')"
                                                                    title="Remove from Whitelist">
                                                                <i class="mdi mdi-delete"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $whitelistedIPs->links() }}
                            @else
                                <div class="text-center py-5">
                                    <i class="mdi mdi-shield-off display-4 text-muted"></i>
                                    <h5 class="mt-3 text-muted">No Whitelisted IPs</h5>
                                    <p class="text-muted">No IP addresses are currently whitelisted.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Recent Access Tab -->
                        <div class="tab-pane fade" id="recent" role="tabpanel">
                            @if($recentAccess->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>IP Address</th>
                                                <th>User</th>
                                                <th>Request</th>
                                                <th>Browser</th>
                                                <th>Status</th>
                                                <th>Time</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentAccess as $access)
                                                <tr class="{{ $access->is_blocked ? 'table-danger' : '' }}">
                                                    <td>
                                                        <code>{{ $access->ip_address }}</code>
                                                    </td>
                                                    <td>
                                                        @if($access->user)
                                                            {{ $access->user->name }}
                                                            <br><small class="text-muted">{{ $access->user->email }}</small>
                                                        @else
                                                            <span class="text-muted">Guest</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <strong>{{ $access->http_method }}</strong> {{ Str::limit($access->request_url, 50) }}
                                                    </td>
                                                    <td>
                                                        <i class="mdi mdi-{{ $access->browser === 'Chrome' ? 'google-chrome' : ($access->browser === 'Firefox' ? 'firefox' : 'web') }}"></i>
                                                        {{ $access->browser }}
                                                    </td>
                                                    <td>
                                                        @if($access->is_blocked)
                                                            <span class="badge bg-danger">Blocked</span>
                                                        @else
                                                            <span class="badge bg-success">Allowed</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $access->created_at->format('H:i:s') }}
                                                        <br><small class="text-muted">{{ $access->created_at->diffForHumans() }}</small>
                                                    </td>
                                                    <td>
                                                        @if(!$access->is_blocked)
                                                            <button type="button" 
                                                                    class="btn btn-outline-danger btn-sm" 
                                                                    onclick="quickBlockIP('{{ $access->ip_address }}')"
                                                                    title="Block this IP">
                                                                <i class="mdi mdi-shield-off"></i>
                                                            </button>
                                                        @endif
                                                        <button type="button" 
                                                                class="btn btn-outline-success btn-sm" 
                                                                onclick="quickWhitelistIP('{{ $access->ip_address }}')"
                                                                title="Whitelist this IP">
                                                            <i class="mdi mdi-shield-check"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="mdi mdi-clock display-4 text-muted"></i>
                                    <h5 class="mt-3 text-muted">No Recent Access</h5>
                                    <p class="text-muted">No access attempts recorded recently.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Block Modal -->
<div class="modal fade" id="quickBlockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Block IP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.ip.block') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="quick_ip" class="form-label">IP Address</label>
                        <input type="text" class="form-control" id="quick_ip" name="ip_address" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="quick_reason" class="form-label">Reason <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="quick_reason" name="reason" placeholder="Suspicious activity" required>
                    </div>
                    <div class="mb-3">
                        <label for="quick_duration" class="form-label">Duration (Hours)</label>
                        <select class="form-select" id="quick_duration" name="duration">
                            <option value="">Permanent</option>
                            <option value="1">1 Hour</option>
                            <option value="24">24 Hours</option>
                            <option value="168">1 Week</option>
                            <option value="720">1 Month</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Block IP</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick Whitelist Modal -->
<div class="modal fade" id="quickWhitelistModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Whitelist IP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.ip.whitelist') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="quick_whitelist_ip" class="form-label">IP Address</label>
                        <input type="text" class="form-control" id="quick_whitelist_ip" name="ip_address" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="quick_description" class="form-label">Description <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="quick_description" name="description" placeholder="Trusted source" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Whitelist IP</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function quickBlockIP(ipAddress) {
    document.getElementById('quick_ip').value = ipAddress;
    const modal = new bootstrap.Modal(document.getElementById('quickBlockModal'));
    modal.show();
}

function quickWhitelistIP(ipAddress) {
    document.getElementById('quick_whitelist_ip').value = ipAddress;
    const modal = new bootstrap.Modal(document.getElementById('quickWhitelistModal'));
    modal.show();
}

// Auto-refresh recent access tab every 30 seconds
setInterval(function() {
    if (document.getElementById('recent-tab').classList.contains('active')) {
        // Only refresh if recent tab is active and no modal is open
        if (!document.querySelector('.modal.show')) {
            window.location.reload();
        }
    }
}, 30000);
</script>

@endsection