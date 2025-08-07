{{-- Create this file: resources/views/test-analytics-simple.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.9.96/css/materialdesignicons.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success">
                    <h4><i class="mdi mdi-check-circle me-2"></i>Question Analytics Test is Working!</h4>
                    <p>If you can see this page, the basic routing and view system is working.</p>
                </div>
                
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5><i class="mdi mdi-poll me-2"></i>Test Analytics Dashboard</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="mdi mdi-help-circle display-4 text-primary mb-2"></i>
                                        <h4 class="text-primary">25</h4>
                                        <small>Total Questions</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="mdi mdi-chart-line display-4 text-success mb-2"></i>
                                        <h4 class="text-success">67.5%</h4>
                                        <small>Avg Success Rate</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="mdi mdi-account-group display-4 text-info mb-2"></i>
                                        <h4 class="text-info">450</h4>
                                        <small>Total Attempts</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <i class="mdi mdi-star display-4 text-warning mb-2"></i>
                                        <h4 class="text-warning">85%</h4>
                                        <small>Quality Score</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-12">
                                <h6>Sample Question Analytics Table:</h6>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Question</th>
                                                <th>Type</th>
                                                <th>Success Rate</th>
                                                <th>Difficulty</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>What is the capital of France?</td>
                                                <td>Multiple Choice</td>
                                                <td>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-success" style="width: 84%"></div>
                                                    </div>
                                                    <small>84%</small>
                                                </td>
                                                <td><span class="badge bg-success">Easy</span></td>
                                                <td><i class="mdi mdi-check-circle text-success"></i> Working</td>
                                            </tr>
                                            <tr>
                                                <td>Explain theory of relativity</td>
                                                <td>Fill in Blank</td>
                                                <td>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-danger" style="width: 25%"></div>
                                                    </div>
                                                    <small>25%</small>
                                                </td>
                                                <td><span class="badge bg-danger">Very Hard</span></td>
                                                <td><i class="mdi mdi-check-circle text-success"></i> Working</td>
                                            </tr>
                                            <tr>
                                                <td>True or False: Earth is round</td>
                                                <td>True/False</td>
                                                <td>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-success" style="width: 97%"></div>
                                                    </div>
                                                    <small>97%</small>
                                                </td>
                                                <td><span class="badge bg-success">Easy</span></td>
                                                <td><i class="mdi mdi-check-circle text-success"></i> Working</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="text-center">
                            <p class="text-muted">
                                If you can see this page with proper styling and icons, 
                                the Question Analytics feature is ready to be implemented!
                            </p>
                            <button class="btn btn-primary me-2">
                                <i class="mdi mdi-chart-bar me-1"></i>View Analytics
                            </button>
                            <button class="btn btn-success me-2">
                                <i class="mdi mdi-download me-1"></i>Export Data
                            </button>
                            <button class="btn btn-info">
                                <i class="mdi mdi-compare me-1"></i>Compare Questions
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>