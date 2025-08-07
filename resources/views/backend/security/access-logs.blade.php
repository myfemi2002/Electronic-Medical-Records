@extends('admin.admin_master')
@section('title', 'Access Logs')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Access Logs</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.ip.index') }}">IP Management</a></li>
                    <li class="breadcrumb-item active">Access Logs</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-filter me-2"></i>Filter Access Logs
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.ip.access-logs') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="ip_filter" class="form-label">IP Address</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="ip_filter" 
                                   name="ip_filter" 
                                   value="{{ request('ip_filter') }}" 
                                   placeholder="192.168.1.1">
                        </div>
                        <div class="col-md-2">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_from" 
                                   name="date_from" 
                                   value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_to" 
                                   name="date_to" 
                                   value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="status_filter" class="form-label">Status</label>
                            <select class="form-select" id="status_filter" name="status_filter">
                                <option value="">All Status</option>
                                <option value="allowed" {{ request('status_filter') === 'allowed' ? 'selected' : '' }}>Allowed</option>
                                <option value="blocked" {{ request('status_filter') === 'blocked' ? 'selected' : '' }}>Blocked</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-magnify me-1"></i>Filter
                                </button>
                                <a href="{{ route('admin.ip.access-logs') }}" class="btn btn-outline-secondary">
                                    <i class="mdi mdi-refresh me-1"></i>Clear
                                </a>
                                <a href="{{ route('admin.ip.export-logs') }}?{{ http_build_query(request()->all()) }}" 
                                   class="btn btn-success">
                                    <i class="mdi mdi-download me-1"></i>Export
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="mdi mdi-eye display-4 text-info"></i>
                    <h3 class="mt-2">{{ $logs->total() }}</h3>
                    <p class="mb-0">Total Logs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="mdi mdi-check-circle display-4 text-success"></i>
                    <h3 class="mt-2">{{ $logs->where('is_blocked', false)->count() }}</h3>
                    <p class="mb-0">Allowed</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="mdi mdi-block-helper display-4 text-danger"></i>
                    <h3 class="mt-2">{{ $logs->where('is_blocked', true)->count() }}</h3>
                    <p class="mb-0">Blocked</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="mdi mdi-ip-network display-4 text-warning"></i>
                    <h3 class="mt-2">{{ $logs->unique('ip_address')->count() }}</h3>
                    <p class="mb-0">Unique IPs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Access Logs Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Access Log Entries</h5>
                        <button class="btn btn-outline-info btn-sm" onclick="refreshLogs()">
                            <i class="mdi mdi-refresh me-1"></i>Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($logs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Time</th>
                                        <th>IP Address</th>
                                        <th>User</th>
                                        <th>Request</th>
                                        <th>Browser</th>
                                        <th>Status</th>
                                        <th>Location</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logs as $log)
                                        <tr class="{{ $log->is_blocked ? 'table-danger' : '' }}">
                                            <td>
                                                <div>
                                                    {{ $log->created_at->format('M d, H:i:s') }}
                                                </div>
                                                <small class="text-muted">
                                                    {{ $log->created_at->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td>
                                                <code>{{ $log->ip_address }}</code>
                                                @if($log->ip_address === request()->ip())
                                                    <br><small class="text-primary fw-bold">Your IP</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($log->user)
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-2">
                                                            <span class="avatar-title bg-primary rounded-circle">
                                                                {{ substr($log->user->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $log->user->name }}</h6>
                                                            <small class="text-muted">{{ $log->user->email }}</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">
                                                        <i class="mdi mdi-account-question me-1"></i>Guest
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <span class="badge bg-{{ $log->http_method === 'GET' ? 'info' : ($log->http_method === 'POST' ? 'warning' : 'secondary') }}">
                                                        {{ $log->http_method }}
                                                    </span>
                                                </div>
                                                <small class="text-muted" title="{{ $log->request_url }}">
                                                    {{ Str::limit($log->request_url, 50) }}
                                                </small>
                                            </td>
                                            <td>
                                                <div>
                                                    <i class="mdi mdi-{{ $log->browser === 'Chrome' ? 'google-chrome' : ($log->browser === 'Firefox' ? 'firefox' : ($log->browser === 'Safari' ? 'apple-safari' : 'web')) }} me-1"></i>
                                                    {{ $log->browser }}
                                                </div>
                                                <small class="text-muted" title="{{ $log->user_agent }}">
                                                    {{ Str::limit($log->user_agent, 30) }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($log->is_blocked)
                                                    <span class="badge bg-danger">
                                                        <i class="mdi mdi-block-helper me-1"></i>Blocked
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">
                                                        <i class="mdi mdi-check-circle me-1"></i>Allowed
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <i class="mdi mdi-map-marker text-muted me-1"></i>
                                                {{ $log->location ?? 'Unknown' }}
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    @if(!$log->is_blocked)
                                                        <button type="button" 
                                                                class="btn btn-outline-danger btn-sm" 
                                                                onclick="quickBlockIP('{{ $log->ip_address }}')"
                                                                title="Block this IP">
                                                            <i class="mdi mdi-shield-off"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    <button type="button" 
                                                            class="btn btn-outline-success btn-sm" 
                                                            onclick="quickWhitelistIP('{{ $log->ip_address }}')"
                                                            title="Whitelist this IP">
                                                        <i class="mdi mdi-shield-check"></i>
                                                    </button>
                                                    
                                                    <button type="button" 
                                                            class="btn btn-outline-info btn-sm" 
                                                            onclick="viewLogDetails({{ $log->id }})"
                                                            title="View Details">
                                                        <i class="mdi mdi-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <small class="text-muted">
                                    Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} entries
                                </small>
                            </div>
                            <div>
                                {{ $logs->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-file-document-outline display-4 text-muted"></i>
                            <h5 class="mt-3 text-muted">No Access Logs Found</h5>
                            <p class="text-muted">
                                @if(request()->hasAny(['ip_filter', 'date_from', 'date_to', 'status_filter']))
                                    No logs match your current filters. Try adjusting the filter criteria.
                                @else
                                    No access logs have been recorded yet.
                                @endif
                            </p>
                            @if(request()->hasAny(['ip_filter', 'date_from', 'date_to', 'status_filter']))
                                <a href="{{ route('admin.ip.access-logs') }}" class="btn btn-primary">
                                    <i class="mdi mdi-refresh me-2"></i>Clear Filters
                                </a>
                            @endif
                        </div>
                    @endif
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

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Access Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="logDetailsContent">
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
function refreshLogs() {
    window.location.reload();
}

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

function viewLogDetails(logId) {
    const modal = new bootstrap.Modal(document.getElementById('logDetailsModal'));
    const content = document.getElementById('logDetailsContent');
    
    // Show loading
    content.innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    
    modal.show();
    
    // For now, show a placeholder - you can extend this to fetch actual log details
    setTimeout(() => {
        content.innerHTML = `
            <div class="alert alert-info">
                <h6>Log Details</h6>
                <p>Detailed log information would be displayed here. You can extend this functionality to show more comprehensive log data.</p>
            </div>
        `;
    }, 1000);
}

// Auto-refresh every 60 seconds if no modal is open
setInterval(function() {
    if (!document.querySelector('.modal.show')) {
        // Only refresh if no filters are applied or if user is on first page
        const urlParams = new URLSearchParams(window.location.search);
        if (!urlParams.has('page') || urlParams.get('page') === '1') {
            window.location.reload();
        }
    }
}, 60000);

// Set default date filters to today if none provided
document.addEventListener('DOMContentLoaded', function() {
    const dateFrom = document.getElementById('date_from');
    const dateTo = document.getElementById('date_to');
    
    if (!dateFrom.value && !dateTo.value && !window.location.search.includes('date_from')) {
        const today = new Date().toISOString().split('T')[0];
        dateFrom.value = today;
        dateTo.value = today;
    }
});
</script>

@endsection