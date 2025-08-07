@extends('admin.admin_master')
@section('title', 'Session Report - ' . $quizSession->title)
@section('admin')

<div class="content container-fluid pb-0">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">{{ $quizSession->title }} - Detailed Report</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.session-reports.index') }}">Session Reports</a></li>
                    <li class="breadcrumb-item active">{{ $quizSession->title }}</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Session Overview -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-chart-box me-2"></i>Session Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <h4 class="text-primary">{{ $sessionStats['total_registrations'] }}</h4>
                            <small>Total Registered</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <h4 class="text-success">{{ $sessionStats['total_completed'] }}</h4>
                            <small>Completed</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <h4 class="text-info">{{ $sessionStats['completion_rate'] }}%</h4>
                            <small>Completion Rate</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <h4 class="text-warning">{{ $sessionStats['average_score'] }}</h4>
                            <small>Avg Score</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="mdi mdi-timer me-2"></i>Time Analytics</h6>
                </div>
                <div class="card-body text-center">
                    <h5 class="text-primary">{{ $timeAnalytics['average_time_minutes'] }} min</h5>
                    <small class="text-muted">Average Time</small>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <strong>{{ $timeAnalytics['fastest_time_minutes'] }} min</strong>
                            <br><small>Fastest</small>
                        </div>
                        <div class="col-6">
                            <strong>{{ $timeAnalytics['slowest_time_minutes'] }} min</strong>
                            <br><small>Slowest</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exam Type Performance -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-clipboard-list me-2"></i>Performance by Exam Type</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Exam Type</th>
                                    <th>Registered</th>
                                    <th>Completed</th>
                                    <th>Completion %</th>
                                    <th>Avg Score</th>
                                    <th>Avg %</th>
                                    <th>Questions</th>
                                    <th>Time Expired</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($examTypeStats as $stat)
                                    <tr>
                                        <td><strong>{{ $stat['exam_type']->name }}</strong></td>
                                        <td>{{ $stat['total_registrations'] }}</td>
                                        <td><span class="text-success">{{ $stat['total_completed'] }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress me-2" style="width: 40px; height: 6px;">
                                                    <div class="progress-bar bg-success" style="width: {{ $stat['completion_rate'] }}%"></div>
                                                </div>
                                                {{ $stat['completion_rate'] }}%
                                            </div>
                                        </td>
                                        <td>{{ $stat['average_score'] }}</td>
                                        <td><span class="badge bg-primary">{{ $stat['average_percentage'] }}%</span></td>
                                        <td>{{ $stat['questions_count'] }}</td>
                                        <td>
                                            @if($stat['time_expired_count'] > 0)
                                                <span class="text-warning">{{ $stat['time_expired_count'] }}</span>
                                            @else
                                                <span class="text-muted">0</span>
                                            @endif
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

    <!-- Top Performers & Regional Analysis -->
    <div class="row mb-4">
        <!-- Top Performers -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="mdi mdi-trophy me-2"></i>Top Performers (by Score & Time)</h6>
                </div>
                <div class="card-body">
                    @if($performanceData['top_performers']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Email</th>
                                        <th>Score</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($performanceData['top_performers']->take(10) as $index => $performer)
                                        @php
                                            $reg = $performer['registration'];
                                            $timeTaken = ($reg->quiz_started_at && $reg->quiz_taken_at) ? 
                                                $reg->quiz_started_at->diffInMinutes($reg->quiz_taken_at) : 'N/A';
                                        @endphp
                                        <tr>
                                            <td>
                                                @if($index === 0)
                                                    <i class="mdi mdi-trophy text-warning"></i>
                                                @elseif($index === 1)
                                                    <i class="mdi mdi-medal text-secondary"></i>
                                                @elseif($index === 2)
                                                    <i class="mdi mdi-medal text-bronze"></i>
                                                @else
                                                    {{ $index + 1 }}
                                                @endif
                                            </td>
                                            <td>{{ Str::limit($reg->email, 20) }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $performer['percentage'] }}%</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $timeTaken }} min</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No completed quizzes yet</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Regional Analysis -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="mdi mdi-map me-2"></i>Regional Performance</h6>
                </div>
                <div class="card-body">
                    @if($regionalAnalysis->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Region</th>
                                        <th>Completed</th>
                                        <th>Avg %</th>
                                        <th>Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regionalAnalysis->take(10) as $region)
                                        <tr>
                                            <td><strong>{{ $region['region'] }}</strong></td>
                                            <td>{{ $region['total_completed'] }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $region['average_percentage'] }}%</span>
                                            </td>
                                            <td>
                                                <div class="progress" style="width: 40px; height: 6px;">
                                                    <div class="progress-bar bg-success" style="width: {{ $region['completion_rate'] }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No regional data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Question Difficulty & Score Distribution -->
    <div class="row mb-4">
        <!-- Question Difficulty -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="mdi mdi-help-circle me-2"></i>Most Difficult Questions</h6>
                </div>
                <div class="card-body">
                    @if($questionAnalysis->count() > 0)
                        @foreach($questionAnalysis->take(5) as $analysis)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <small><strong>{{ Str::limit($analysis['question']->question, 40) }}</strong></small>
                                    <span class="badge bg-{{ $analysis['correct_percentage'] >= 60 ? 'success' : 'danger' }}">
                                        {{ $analysis['correct_percentage'] }}%
                                    </span>
                                </div>
                                <div class="progress mt-1" style="height: 4px;">
                                    <div class="progress-bar bg-{{ $analysis['correct_percentage'] >= 60 ? 'success' : 'danger' }}" 
                                         style="width: {{ $analysis['correct_percentage'] }}%"></div>
                                </div>
                                <small class="text-muted">{{ $analysis['exam_type'] }} • {{ $analysis['difficulty_level'] }}</small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No question data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Score Distribution -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="mdi mdi-chart-pie me-2"></i>Score Distribution</h6>
                </div>
                <div class="card-body">
                    @if(array_sum($performanceData['score_distribution']) > 0)
                        @foreach($performanceData['score_distribution'] as $range => $count)
                            @if($count > 0)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>{{ $range }}</span>
                                    <div class="d-flex align-items-center">
                                        <div class="progress me-2" style="width: 80px; height: 6px;">
                                            @php
                                                $total = array_sum($performanceData['score_distribution']);
                                                $percentage = ($count / $total) * 100;
                                            @endphp
                                            <div class="progress-bar bg-primary" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="badge bg-secondary">{{ $count }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        
                        <hr>
                        <div class="row text-center">
                            <div class="col-6">
                                <h6 class="text-success">{{ $performanceData['pass_fail_stats']['passed'] }}</h6>
                                <small>Passed</small>
                            </div>
                            <div class="col-6">
                                <h6 class="text-danger">{{ $performanceData['pass_fail_stats']['failed'] }}</h6>
                                <small>Failed</small>
                            </div>
                        </div>
                    @else
                        <p class="text-muted text-center">No score data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Export Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="mb-3"><i class="mdi mdi-download me-2"></i>Export Report</h6>
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.session-reports.export', ['session' => $quizSession->id, 'format' => 'csv']) }}" 
                           class="btn btn-success">
                            <i class="mdi mdi-file-excel me-1"></i>Export CSV
                        </a>
                        <a href="{{ route('admin.leaderboard.session', $quizSession->id) }}" 
                           class="btn btn-primary">
                            <i class="mdi mdi-trophy me-1"></i>View Leaderboard
                        </a>
                        <a href="{{ route('admin.quiz-sessions.manage', $quizSession->id) }}" 
                           class="btn btn-outline-secondary">
                            <i class="mdi mdi-cog me-1"></i>Manage Session
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.text-bronze { color: #cd7f32 !important; }
.progress { background-color: #e9ecef; }
</style>

@endsection