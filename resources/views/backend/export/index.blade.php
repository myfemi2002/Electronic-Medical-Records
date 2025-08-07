@extends('admin.admin_master')
@section('title', 'Export Data')
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Export Data</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item active">Export Data</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Export Overview -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="mdi mdi-calendar-multiple display-4 text-primary mb-2"></i>
                    <h4 class="text-primary">{{ $exportStats['total_sessions'] }}</h4>
                    <small>Quiz Sessions</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="mdi mdi-account-group display-4 text-info mb-2"></i>
                    <h4 class="text-info">{{ $exportStats['total_registrations'] }}</h4>
                    <small>Total Registrations</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="mdi mdi-check-circle display-4 text-success mb-2"></i>
                    <h4 class="text-success">{{ $exportStats['total_completed'] }}</h4>
                    <small>Completed Quizzes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="mdi mdi-help-circle display-4 text-warning mb-2"></i>
                    <h4 class="text-warning">{{ $exportStats['total_questions'] }}</h4>
                    <small>Total Questions</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="row">
        <!-- Quiz Sessions Export -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-calendar-multiple me-2"></i>Export Quiz Sessions</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Export quiz session data including titles, dates, status, and summary statistics.</p>
                    
                    <form action="{{ route('admin.export.sessions') }}" method="POST" class="export-form">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Select Sessions (Optional)</label>
                            <select name="session_ids[]" class="form-select" multiple size="5">
                                @foreach($quizSessions as $session)
                                    <option value="{{ $session->id }}">{{ $session->title }} ({{ $session->quiz_date->format('M d, Y') }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Leave empty to export all sessions</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Export Format</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" value="csv" id="sessions_csv" checked>
                                <label class="form-check-label" for="sessions_csv">CSV (Excel Compatible)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" value="json" id="sessions_json">
                                <label class="form-check-label" for="sessions_json">JSON (API/Development)</label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-download me-1"></i>Export Sessions
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Registrations Export -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0 text-white"><i class="mdi mdi-account-multiple me-2"></i>Export Registrations</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Export student registration data with scores, timing, and demographic information.</p>
                    
                    <form action="{{ route('admin.export.registrations') }}" method="POST" class="export-form">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Quiz Session</label>
                                <select name="session_id" class="form-select">
                                    <option value="">All Sessions</option>
                                    @foreach($quizSessions as $session)
                                        <option value="{{ $session->id }}">{{ $session->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Exam Type</label>
                                <select name="exam_type_id" class="form-select">
                                    <option value="">All Types</option>
                                    @foreach($examTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="completed">Completed Only</option>
                                    <option value="incomplete">Incomplete Only</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Format</label>
                                <select name="format" class="form-select">
                                    <option value="csv">CSV</option>
                                    <option value="json">JSON</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">From Date</label>
                                <input type="date" name="date_from" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">To Date</label>
                                <input type="date" name="date_to" class="form-control">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-info">
                            <i class="mdi mdi-download me-1"></i>Export Registrations
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Questions Export -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="mdi mdi-help-circle me-2"></i>Export Questions</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Export question bank data including content, options, correct answers, and metadata.</p>
                    
                    <form action="{{ route('admin.export.questions') }}" method="POST" class="export-form">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Quiz Session</label>
                            <select name="session_id" class="form-select">
                                <option value="">All Sessions</option>
                                @foreach($quizSessions as $session)
                                    <option value="{{ $session->id }}">{{ $session->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Exam Type</label>
                                <select name="exam_type_id" class="form-select">
                                    <option value="">All Types</option>
                                    @foreach($examTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Question Type</label>
                                <select name="question_type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="multiple_choice">Multiple Choice</option>
                                    <option value="true_false">True/False</option>
                                    <option value="fill_in_blank">Fill in Blank</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Export Format</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" value="csv" id="questions_csv" checked>
                                <label class="form-check-label" for="questions_csv">CSV (Excel Compatible)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" value="json" id="questions_json">
                                <label class="form-check-label" for="questions_json">JSON (API/Development)</label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-download me-1"></i>Export Questions
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Analytics Export -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="mdi mdi-chart-line me-2"></i>Export Analytics</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Export comprehensive analytics reports including performance data and insights.</p>
                    
                    <form action="{{ route('admin.export.analytics') }}" method="POST" class="export-form">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Date Range</label>
                            <select name="date_range" class="form-select">
                                <option value="7">Last 7 days</option>
                                <option value="30" selected>Last 30 days</option>
                                <option value="90">Last 90 days</option>
                                <option value="365">Last year</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Export Format</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" value="csv" id="analytics_csv" checked>
                                <label class="form-check-label" for="analytics_csv">CSV (Excel Compatible)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" value="json" id="analytics_json">
                                <label class="form-check-label" for="analytics_json">JSON (API/Development)</label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-warning">
                            <i class="mdi mdi-download me-1"></i>Export Analytics
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Export Options -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-white">
                    <h5 class="mb-0 text-white"><i class="mdi mdi-tune me-2"></i>Custom Export Options</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Export specialized reports and custom data sets for advanced analysis.</p>
                    
                    <form action="{{ route('admin.export.custom') }}" method="POST" class="export-form">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Export Type</label>
                                <select name="export_type" class="form-select" required>
                                    <option value="">Select Export Type</option>
                                    <option value="performance_by_region">Performance by Region</option>
                                    <option value="time_analysis">Time Analysis Report</option>
                                    <option value="difficulty_breakdown">Question Difficulty Breakdown</option>
                                    <option value="top_performers">Top 100 Performers</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date Range</label>
                                <select name="date_range" class="form-select" required>
                                    <option value="7">Last 7 days</option>
                                    <option value="30">Last 30 days</option>
                                    <option value="90">Last 90 days</option>
                                    <option value="180">Last 6 months</option>
                                    <option value="365">Last year</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Format</label>
                                <select name="format" class="form-select" required>
                                    <option value="csv">CSV</option>
                                    <option value="json">JSON</option>
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-dark">
                            <i class="mdi mdi-download me-1"></i>Export Custom Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Export History (Optional) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-history me-2"></i>Export Guidelines</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">CSV Format</h6>
                            <ul class="list-unstyled">
                                <li><i class="mdi mdi-check text-success me-1"></i>Excel compatible</li>
                                <li><i class="mdi mdi-check text-success me-1"></i>Easy to analyze</li>
                                <li><i class="mdi mdi-check text-success me-1"></i>Smaller file size</li>
                                <li><i class="mdi mdi-check text-success me-1"></i>Wide compatibility</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-info">JSON Format</h6>
                            <ul class="list-unstyled">
                                <li><i class="mdi mdi-check text-success me-1"></i>API integration ready</li>
                                <li><i class="mdi mdi-check text-success me-1"></i>Preserves data structure</li>
                                <li><i class="mdi mdi-check text-success me-1"></i>Developer friendly</li>
                                <li><i class="mdi mdi-check text-success me-1"></i>Machine readable</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <i class="mdi mdi-information me-2"></i>
                        <strong>Note:</strong> Large exports may take some time to process. Please be patient and do not refresh the page during export.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
.export-form {
    
    padding: 1rem;
    border-radius: 0.375rem;
    border: 1px solid #dee2e6;
}

.card-hover:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.export-form button[type="submit"] {
    width: 100%;
}

@media (max-width: 768px) {
    .export-form button[type="submit"] {
        margin-top: 1rem;
    }
}
</style>
@endpush

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading state to export forms
    document.querySelectorAll('.export-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-1"></i>Exporting...';
            
            // Re-enable button after 10 seconds (in case of issues)
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }, 10000);
        });
    });
    
    // Add hover effects to cards
    document.querySelectorAll('.card').forEach(card => {
        card.classList.add('card-hover');
    });
    
    // Show success notification if redirected back
    @if(session('message'))
        showNotification('{{ session('message') }}', '{{ session('alert-type', 'info') }}');
    @endif
});

function showNotification(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = 'position-fixed top-0 end-0 p-3';
    toast.style.zIndex = '9999';
    toast.innerHTML = `
        <div class="toast show" role="alert">
            <div class="toast-header">
                <i class="mdi mdi-${type === 'success' ? 'check-circle text-success' : (type === 'error' ? 'alert-circle text-danger' : 'information text-info')} me-2"></i>
                <strong class="me-auto">Export</strong>
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