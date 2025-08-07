@extends('admin.admin_master')
@section('title', 'Question Flags & Review')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Question Flags & Review</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item active">Flag Review</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <i class="mdi mdi-flag display-4 text-warning mb-2"></i>
                    <h4 class="text-warning">{{ $summaryStats['total_flags'] }}</h4>
                    <small>Total Flags</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <i class="mdi mdi-clock-alert display-4 text-danger mb-2"></i>
                    <h4 class="text-danger">{{ $summaryStats['pending_flags'] }}</h4>
                    <small>Pending Review</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <i class="mdi mdi-alert-circle display-4 text-danger mb-2"></i>
                    <h4 class="text-danger">{{ $summaryStats['high_priority'] }}</h4>
                    <small>High Priority</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="mdi mdi-eye display-4 text-info mb-2"></i>
                    <h4 class="text-info">{{ $summaryStats['under_review'] }}</h4>
                    <small>Under Review</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="mdi mdi-check-circle display-4 text-success mb-2"></i>
                    <h4 class="text-success">{{ $summaryStats['resolved_today'] }}</h4>
                    <small>Resolved Today</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-filter me-2"></i>Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.flags.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="under_review" {{ $status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="resolved" {{ $status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="dismissed" {{ $status == 'dismissed' ? 'selected' : '' }}>Dismissed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Priority</label>
                            <select name="priority" class="form-select">
                                <option value="all" {{ $priority == 'all' ? 'selected' : '' }}>All Priorities</option>
                                <option value="high" {{ $priority == 'high' ? 'selected' : '' }}>High</option>
                                <option value="medium" {{ $priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="low" {{ $priority == 'low' ? 'selected' : '' }}>Low</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Flag Type</label>
                            <select name="flag_type" class="form-select">
                                <option value="all" {{ $flagType == 'all' ? 'selected' : '' }}>All Types</option>
                                <option value="poor_performance" {{ $flagType == 'poor_performance' ? 'selected' : '' }}>Poor Performance</option>
                                <option value="poor_discrimination" {{ $flagType == 'poor_discrimination' ? 'selected' : '' }}>Poor Discrimination</option>
                                <option value="ambiguous_wording" {{ $flagType == 'ambiguous_wording' ? 'selected' : '' }}>Ambiguous Wording</option>
                                <option value="incorrect_answer" {{ $flagType == 'incorrect_answer' ? 'selected' : '' }}>Incorrect Answer</option>
                                <option value="too_difficult" {{ $flagType == 'too_difficult' ? 'selected' : '' }}>Too Difficult</option>
                                <option value="too_easy" {{ $flagType == 'too_easy' ? 'selected' : '' }}>Too Easy</option>
                                <option value="other" {{ $flagType == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-magnify me-1"></i>Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button type="button" class="btn btn-outline-primary" onclick="selectAllFlags()">
                        <i class="mdi mdi-select-all me-1"></i>Select All
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="clearSelection()">
                        <i class="mdi mdi-close me-1"></i>Clear
                    </button>
                    <button type="button" class="btn btn-success" onclick="bulkAction('resolve')" disabled id="bulkResolveBtn">
                        <i class="mdi mdi-check me-1"></i>Resolve Selected
                    </button>
                    <button type="button" class="btn btn-warning" onclick="bulkAction('review')" disabled id="bulkReviewBtn">
                        <i class="mdi mdi-eye me-1"></i>Mark for Review
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="bulkAction('dismiss')" disabled id="bulkDismissBtn">
                        <i class="mdi mdi-close-circle me-1"></i>Dismiss Selected
                    </button>
                </div>
                <div>
                    <a href="{{ route('admin.flags.export', request()->query()) }}" class="btn btn-info">
                        <i class="mdi mdi-download me-1"></i>Export Report
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Flags Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-flag me-2"></i>Question Flags</h5>
                </div>
                <div class="card-body">
                    @if($flags->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="30">
                                            <input type="checkbox" id="selectAllCheckbox" onchange="toggleAllFlags()">
                                        </th>
                                        <th>Question</th>
                                        <th>Flag Type</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Flagged By</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($flags as $flag)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="flag-checkbox" value="{{ $flag->id }}">
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ Str::limit($flag->question->question, 60) }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $flag->question->quizSession->title }} • {{ $flag->question->examType->name }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $flag->getFlagTypeLabel() }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $flag->getPriorityColor() }}">{{ ucfirst($flag->priority) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $flag->getStatusColor() }}">{{ ucfirst(str_replace('_', ' ', $flag->status)) }}</span>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $flag->flaggedBy->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $flag->flaggedBy->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $flag->created_at->format('M d, Y') }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $flag->created_at->diffForHumans() }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.flags.show', $flag->id) }}" 
                                                       class="btn btn-sm btn-primary" title="View Details">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>
                                                    
                                                    @if($flag->status === 'pending')
                                                        <button class="btn btn-sm btn-success" 
                                                                onclick="updateFlagStatus({{ $flag->id }}, 'under_review')"
                                                                title="Mark for Review">
                                                            <i class="mdi mdi-eye-settings"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    @if(in_array($flag->status, ['pending', 'under_review']))
                                                        <button class="btn btn-sm btn-warning" 
                                                                onclick="updateFlagStatus({{ $flag->id }}, 'resolved')"
                                                                title="Resolve">
                                                            <i class="mdi mdi-check"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-secondary" 
                                                                onclick="updateFlagStatus({{ $flag->id }}, 'dismissed')"
                                                                title="Dismiss">
                                                            <i class="mdi mdi-close"></i>
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
                        <div class="mt-3">
                            {{ $flags->withQueryString()->links('pagination::bootstrap-4') }}
                        </div>

                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-flag-outline display-4 text-muted"></i>
                            <h5 class="mt-3 text-muted">No Flags Found</h5>
                            <p class="text-muted">No question flags match your current filters.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Action Modal -->
    <div class="modal fade" id="bulkActionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="bulkActionForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Action</label>
                            <select name="action" id="bulkActionSelect" class="form-select" required>
                                <option value="">Select Action</option>
                                <option value="resolve">Resolve</option>
                                <option value="dismiss">Dismiss</option>
                                <option value="review">Mark for Review</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Review Notes (Optional)</label>
                            <textarea name="review_notes" class="form-control" rows="3" 
                                      placeholder="Add notes about this bulk action..."></textarea>
                        </div>
                        <div id="selectedFlagsInfo" class="alert alert-info">
                            <span id="selectedCount">0</span> flag(s) selected for bulk action.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Apply Action</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
.flag-checkbox:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.card-hover:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.btn-group .btn {
    margin-right: 0.125rem;
}

.table td {
    vertical-align: middle;
}
</style>
@endpush

@push('js')
<script>
let selectedFlags = [];

function toggleAllFlags() {
    const selectAll = document.getElementById('selectAllCheckbox');
    const checkboxes = document.querySelectorAll('.flag-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkButtons();
}

function selectAllFlags() {
    document.getElementById('selectAllCheckbox').checked = true;
    toggleAllFlags();
}

function clearSelection() {
    document.getElementById('selectAllCheckbox').checked = false;
    toggleAllFlags();
}

function updateBulkButtons() {
    const checkboxes = document.querySelectorAll('.flag-checkbox:checked');
    selectedFlags = Array.from(checkboxes).map(cb => cb.value);
    
    const bulkButtons = ['bulkResolveBtn', 'bulkReviewBtn', 'bulkDismissBtn'];
    bulkButtons.forEach(btnId => {
        const btn = document.getElementById(btnId);
        btn.disabled = selectedFlags.length === 0;
    });
    
    // Update modal info
    document.getElementById('selectedCount').textContent = selectedFlags.length;
}

function bulkAction(action) {
    if (selectedFlags.length === 0) {
        showNotification('Please select flags to perform bulk action.', 'warning');
        return;
    }
    
    document.getElementById('bulkActionSelect').value = action;
    const modal = new bootstrap.Modal(document.getElementById('bulkActionModal'));
    modal.show();
}

function updateFlagStatus(flagId, status) {
    if (confirm('Are you sure you want to update this flag status?')) {
        fetch(`/admin/flags/${flagId}/status`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                status: status,
                review_notes: `Status updated to ${status} via quick action`
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                location.reload(); // Reload to show updated status
            } else {
                showNotification(data.message || 'Failed to update flag status', 'error');
            }
        })
        .catch(error => {
            showNotification('An error occurred while updating flag status', 'error');
        });
    }
}

// Handle bulk action form submission
document.getElementById('bulkActionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('flag_ids', JSON.stringify(selectedFlags));
    
    fetch('{{ route("admin.flags.bulk-update") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(response => {
        if (response.ok) {
            showNotification('Bulk action completed successfully!', 'success');
            location.reload();
        } else {
            throw new Error('Bulk action failed');
        }
    })
    .catch(error => {
        showNotification('An error occurred during bulk action', 'error');
    });
});

// Add event listeners to checkboxes
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.flag-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkButtons);
    });
    
    // Add hover effects to cards
    document.querySelectorAll('.card').forEach(card => {
        card.classList.add('card-hover');
    });
});

function showNotification(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = 'position-fixed top-0 end-0 p-3';
    toast.style.zIndex = '9999';
    toast.innerHTML = `
        <div class="toast show" role="alert">
            <div class="toast-header">
                <i class="mdi mdi-${type === 'success' ? 'check-circle text-success' : (type === 'error' ? 'alert-circle text-danger' : 'information text-info')} me-2"></i>
                <strong class="me-auto">Flag Review</strong>
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
}
</script>
@endpush

@endsection