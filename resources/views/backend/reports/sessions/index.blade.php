@extends('admin.admin_master')
@section('title', 'Session Reports')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Session Reports</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item active">Session Reports</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Overview Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="mdi mdi-calendar-clock display-4 text-primary mb-3"></i>
                    <h3 class="text-primary">{{ $totalSessions }}</h3>
                    <p class="mb-0">Total Sessions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="mdi mdi-account-group display-4 text-info mb-3"></i>
                    <h3 class="text-info">{{ $totalRegistrations }}</h3>
                    <p class="mb-0">Total Registrations</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="mdi mdi-check-circle display-4 text-success mb-3"></i>
                    <h3 class="text-success">{{ $totalCompleted }}</h3>
                    <p class="mb-0">Completed Quizzes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="mdi mdi-chart-line display-4 text-warning mb-3"></i>
                    <h3 class="text-warning">{{ $completionRate }}%</h3>
                    <p class="mb-0">Completion Rate</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row mb-4">
        <!-- Sessions List -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="mdi mdi-file-chart me-2"></i>Quiz Sessions
                        </h5>
                        <div class="d-flex gap-2">
                            <select id="statusFilter" class="form-select form-select-sm" style="width: auto;">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="expired">Expired</option>
                            </select>
                            <select id="typeFilter" class="form-select form-select-sm" style="width: auto;">
                                <option value="">All Types</option>
                                <option value="national">National</option>
                                <option value="regional">Regional</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($quizSessions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover" id="sessionsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Session</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Participants</th>
                                        <th>Completion</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($quizSessions as $session)
                                        @php
                                            $totalReg = $session->quizRegistrations->count();
                                            $completed = $session->quizRegistrations->where('is_completed', true)->count();
                                            $completionRate = $totalReg > 0 ? round(($completed / $totalReg) * 100, 1) : 0;
                                        @endphp
                                        <tr class="session-row" 
                                            data-status="{{ $session->status }}" 
                                            data-type="{{ $session->type }}">
                                            <td>
                                                <div>
                                                    <h6 class="mb-1">{{ $session->title }}</h6>
                                                    <small class="text-muted">{{ Str::limit($session->description, 40) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $session->type === 'national' ? 'primary' : 'info' }}">
                                                    {{ ucfirst($session->type) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $session->quiz_date->format('M d, Y') }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $session->quiz_date->diffForHumans() }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($session->status === 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @elseif($session->status === 'expired')
                                                    <span class="badge bg-danger">Expired</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <strong>{{ $totalReg }}</strong>
                                                        <br>
                                                        <small class="text-muted">Registered</small>
                                                    </div>
                                                    <div>
                                                        <strong class="text-success">{{ $completed }}</strong>
                                                        <br>
                                                        <small class="text-muted">Completed</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress me-2" style="width: 60px; height: 8px;">
                                                        <div class="progress-bar 
                                                            @if($completionRate >= 80) bg-success
                                                            @elseif($completionRate >= 60) bg-warning  
                                                            @else bg-danger
                                                            @endif" 
                                                            style="width: {{ $completionRate }}%"></div>
                                                    </div>
                                                    <span class="fw-bold 
                                                        @if($completionRate >= 80) text-success
                                                        @elseif($completionRate >= 60) text-warning  
                                                        @else text-danger
                                                        @endif">{{ $completionRate }}%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.session-reports.show', $session->id) }}" 
                                                       class="btn btn-primary btn-sm"
                                                       title="View Detailed Report">
                                                        <i class="mdi mdi-chart-bar"></i>
                                                    </a>
                                                    <a href="{{ route('admin.session-reports.export', ['session' => $session->id, 'format' => 'csv']) }}" 
                                                       class="btn btn-success btn-sm"
                                                       title="Export CSV">
                                                        <i class="mdi mdi-download"></i>
                                                    </a>
                                                    <a href="{{ route('admin.quiz-sessions.manage', $session->id) }}" 
                                                       class="btn btn-outline-secondary btn-sm"
                                                       title="Manage Session">
                                                        <i class="mdi mdi-cog"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-file-document-box-outline display-4 text-muted"></i>
                            <h5 class="mt-3 text-muted">No Sessions Found</h5>
                            <p class="text-muted">Create quiz sessions to view reports.</p>
                            <a href="{{ route('admin.quiz-sessions.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus me-2"></i>Create Quiz Session
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-clock-outline me-2"></i>Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentActivity->count() > 0)
                        <div class="activity-timeline">
                            @foreach($recentActivity->take(10) as $activity)
                                <div class="activity-item d-flex mb-3">
                                    <div class="activity-icon me-3">
                                        @if($activity['status'] === 'Completed')
                                            <i class="mdi mdi-check-circle text-success"></i>
                                        @else
                                            <i class="mdi mdi-account-plus text-info"></i>
                                        @endif
                                    </div>
                                    <div class="activity-content flex-grow-1">
                                        <div class="activity-text">
                                            <strong>{{ $activity['email'] }}</strong>
                                            <span class="text-muted">{{ strtolower($activity['status']) }}</span>
                                            <strong>{{ $activity['exam_type'] }}</strong>
                                        </div>
                                        <div class="activity-meta">
                                            <small class="text-muted">{{ $activity['session'] }}</small>
                                            <span class="text-muted mx-1">•</span>
                                            <small class="text-muted">{{ $activity['date'] }}</small>
                                            @if($activity['score'] !== 'N/A')
                                                <span class="text-muted mx-1">•</span>
                                                <small class="text-success">{{ $activity['score'] }} pts</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($recentActivity->count() > 10)
                            <div class="text-center mt-3">
                                <small class="text-muted">Showing 10 of {{ $recentActivity->count() }} activities</small>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="mdi mdi-clock-outline display-4 text-muted"></i>
                            <p class="text-muted mt-2">No recent activity</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-chart-pie me-2"></i>Quick Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <i class="mdi mdi-earth text-primary fs-4"></i>
                                <h6 class="mt-2">{{ $quizSessions->where('type', 'national')->count() }}</h6>
                                <small class="text-muted">National</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <i class="mdi mdi-map-marker text-info fs-4"></i>
                                <h6 class="mt-2">{{ $quizSessions->where('type', 'regional')->count() }}</h6>
                                <small class="text-muted">Regional</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <i class="mdi mdi-check-circle text-success fs-4"></i>
                                <h6 class="mt-2">{{ $quizSessions->where('status', 'active')->count() }}</h6>
                                <small class="text-muted">Active</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <i class="mdi mdi-clock-alert text-danger fs-4"></i>
                                <h6 class="mt-2">{{ $quizSessions->where('status', 'expired')->count() }}</h6>
                                <small class="text-muted">Expired</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Overview -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-trending-up me-2"></i>Performance Overview
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $allCompleted = $quizSessions->flatMap(function($session) {
                            return $session->quizRegistrations->where('is_completed', true);
                        });
                        
                        $avgScorePercentage = 0;
                        $highestScorePercentage = 0;
                        
                        if ($allCompleted->count() > 0) {
                            // Calculate average percentage across all sessions
                            $totalPercentage = $allCompleted->sum(function($reg) use ($quizSessions) {
                                $session = $quizSessions->where('id', $reg->quiz_session_id)->first();
                                if ($session) {
                                    $totalPossible = $session->quizQuestions
                                        ->where('exam_type_id', $reg->exam_type_id)
                                        ->sum('points');
                                    return $totalPossible > 0 ? ($reg->score / $totalPossible) * 100 : 0;
                                }
                                return 0;
                            });
                            $avgScorePercentage = round($totalPercentage / $allCompleted->count(), 1);
                            
                            // Calculate highest percentage
                            $highestScorePercentage = $allCompleted->max(function($reg) use ($quizSessions) {
                                $session = $quizSessions->where('id', $reg->quiz_session_id)->first();
                                if ($session) {
                                    $totalPossible = $session->quizQuestions
                                        ->where('exam_type_id', $reg->exam_type_id)
                                        ->sum('points');
                                    return $totalPossible > 0 ? ($reg->score / $totalPossible) * 100 : 0;
                                }
                                return 0;
                            });
                            $highestScorePercentage = round($highestScorePercentage, 1);
                        }
                    @endphp
                    
                    <div class="text-center">
                        <div class="mb-3">
                            <h4 class="text-primary">{{ $avgScorePercentage }}%</h4>
                            <small class="text-muted">Average Score</small>
                        </div>
                        
                        <div class="mb-3">
                            <h4 class="text-success">{{ $highestScorePercentage }}%</h4>
                            <small class="text-muted">Highest Score</small>
                        </div>
                        
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" 
                                 style="width: {{ $avgScorePercentage }}%"></div>
                        </div>
                        <small class="text-muted">Overall Performance</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Filter functionality
document.getElementById('statusFilter').addEventListener('change', filterSessions);
document.getElementById('typeFilter').addEventListener('change', filterSessions);

function filterSessions() {
    const statusFilter = document.getElementById('statusFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    const rows = document.querySelectorAll('.session-row');
    
    rows.forEach(row => {
        const status = row.getAttribute('data-status');
        const type = row.getAttribute('data-type');
        
        let showRow = true;
        
        if (statusFilter && status !== statusFilter) {
            showRow = false;
        }
        
        if (typeFilter && type !== typeFilter) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
    
    // Update visible count
    const visibleRows = document.querySelectorAll('.session-row[style=""], .session-row:not([style])');
    console.log(`Showing ${visibleRows.length} sessions`);
}

// Auto-refresh recent activity every 30 seconds
setInterval(function() {
    // You can implement AJAX refresh here if needed
    console.log('Auto-refresh check...');
}, 30000);

// Export functionality
function exportSession(sessionId, format) {
    window.location.href = `/admin/session-reports/${sessionId}/export?format=${format}`;
}

// Show loading state on export
document.addEventListener('DOMContentLoaded', function() {
    const exportLinks = document.querySelectorAll('a[href*="export"]');
    exportLinks.forEach(link => {
        link.addEventListener('click', function() {
            const icon = this.querySelector('i');
            const originalClass = icon.className;
            icon.className = 'mdi mdi-loading mdi-spin';
            
            setTimeout(() => {
                icon.className = originalClass;
            }, 2000);
        });
    });
});

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

<style>
.activity-timeline {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 0.75rem;
}

.activity-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.activity-icon {
    font-size: 1.2rem;
    width: 24px;
    text-align: center;
}

.progress {
    background-color: #e9ecef;
}

.session-row:hover {
    background-color: rgba(0,0,0,0.02);
}

.btn-group .btn {
    margin-right: 0.125rem;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        margin-bottom: 0.25rem;
        margin-right: 0;
    }
    
    .btn-group .btn:last-child {
        margin-bottom: 0;
    }
    
    .activity-timeline {
        max-height: 300px;
    }
}

/* Responsive table improvements */
@media (max-width: 992px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
    }
}

/* Loading state for export buttons */
.mdi-loading {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Improved spacing for cards */
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: rgba(0, 0, 0, 0.03);
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

/* Activity timeline improvements */
.activity-text strong {
    font-weight: 600;
}

.activity-meta {
    font-size: 0.75rem;
    line-height: 1.2;
    margin-top: 0.25rem;
}

/* Quick stats hover effects */
.border.rounded.p-3:hover {
    background-color: rgba(0, 0, 0, 0.02);
    transform: translateY(-1px);
    transition: all 0.2s ease;
}
</style>

@endsection